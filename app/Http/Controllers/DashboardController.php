<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Route ke dashboard sesuai role
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isHrManager()) {
            return $this->hrManagerDashboard();
        } elseif ($user->isManager()) {
            return $this->managerDashboard();
        } else {
            return $this->employeeDashboard();
        }
    }

    /**
     * Admin Dashboard - Full access to all data
     */
    private function adminDashboard()
    {
        $data = [
            'total_employees' => \App\Models\Employee::where('is_active', true)->count(),
            'total_departments' => \App\Models\Department::where('is_active', true)->count(),
            'total_active_loans' => \App\Models\Loan::where('status', 'active')->count(),
            'pending_leave_requests' => \App\Models\LeaveRequest::where('status', 'pending')->count(),
        ];

        // Get payroll statistics
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $payrollStats = \App\Models\PayrollTransaction::whereMonth('payroll_date', $currentMonth)
                                                      ->whereYear('payroll_date', $currentYear)
                                                      ->selectRaw('SUM(gross_salary) as total_gross, SUM(total_deduction) as total_deduction, SUM(net_salary) as total_net, COUNT(*) as count')
                                                      ->first();

        $data['payroll_stats'] = $payrollStats;

        return view('dashboard.admin', $data);
    }

    /**
     * HR Manager Dashboard
     */
    private function hrManagerDashboard()
    {
        $data = [
            'total_employees' => \App\Models\Employee::where('is_active', true)->count(),
            'pending_leave_requests' => \App\Models\LeaveRequest::where('status', 'pending')->count(),
            'this_month_payroll' => \App\Models\PayrollTransaction::whereMonth('payroll_date', now()->month)
                                                                  ->whereYear('payroll_date', now()->year)
                                                                  ->count(),
        ];

        return view('dashboard.hr-manager', $data);
    }

    /**
     * Manager Department Dashboard
     */
    private function managerDashboard()
    {
        $manager = auth()->user();
        $department = $manager->managedDepartment;

        $data = [
            'team_members' => $department ? $department->employees()->where('is_active', true)->count() : 0,
            'pending_leave_requests' => \App\Models\LeaveRequest::whereHas('employee', function ($q) use ($department) {
                                            if ($department) {
                                                $q->where('department_id', $department->id);
                                            }
                                        })
                                        ->where('status', 'pending')
                                        ->count(),
            'department' => $department,
        ];

        return view('dashboard.manager', $data);
    }

    /**
     * Employee Dashboard
     */
    private function employeeDashboard()
    {
        $employee = auth()->user()->employee;
        $currentYear = now()->year;

        $data = [
            'employee' => $employee,
            'leave_balance' => \App\Models\EmployeeLeaveBalance::where('employee_id', $employee->id)
                                                               ->where('year', $currentYear)
                                                               ->first(),
            'pending_leave_requests' => \App\Models\LeaveRequest::where('employee_id', $employee->id)
                                                               ->where('status', 'pending')
                                                               ->count(),
            'recent_payroll' => \App\Models\PayrollTransaction::where('employee_id', $employee->id)
                                                              ->orderBy('payroll_date', 'desc')
                                                              ->limit(3)
                                                              ->get(),
            'today_attendance' => \App\Models\Attendance::where('employee_id', $employee->id)
                                                       ->where('attendance_date', now()->toDateString())
                                                       ->first(),
        ];

        return view('dashboard.employee', $data);
    }
}
