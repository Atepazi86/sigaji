<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Employee Check-in action
     */
    public function checkin(Request $request)
    {
        $employee = auth()->user()->employee;
        $today = now()->toDateString();

        // Check if already checked in today
        $attendance = Attendance::where('employee_id', $employee->id)
                                ->where('attendance_date', $today)
                                ->first();

        if ($attendance && $attendance->check_in_time) {
            return back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        // Calculate tardiness
        $checkInTime = now();
        $salaryTemplate = $employee->salaryTemplate;
        $allowedTardinessMinutes = $salaryTemplate->allowed_tardiness_minutes ?? 10;

        // Define working start time (default: 08:00)
        $workingStartTime = now()->setHour(8)->setMinute(0)->setSecond(0);
        $tardinessMinutes = $checkInTime->diffInMinutes($workingStartTime);
        $tardinessMinutes = $tardinessMinutes > 0 ? $tardinessMinutes : 0;

        if (!$attendance) {
            // Create new attendance record
            Attendance::create([
                'employee_id' => $employee->id,
                'attendance_date' => $today,
                'status' => 'Hadir',
                'check_in_time' => $checkInTime,
                'tardiness_minutes' => $tardinessMinutes,
            ]);
        } else {
            // Update existing record
            $attendance->update([
                'status' => 'Hadir',
                'check_in_time' => $checkInTime,
                'tardiness_minutes' => $tardinessMinutes,
            ]);
        }

        $message = $tardinessMinutes > $allowedTardinessMinutes 
                   ? "Check-in berhasil. ⚠️ Anda terlambat {$tardinessMinutes} menit." 
                   : 'Check-in berhasil. ✓';

        return back()->with('success', $message);
    }

    /**
     * Employee Check-out action
     */
    public function checkout(Request $request)
    {
        $employee = auth()->user()->employee;
        $today = now()->toDateString();

        $attendance = Attendance::where('employee_id', $employee->id)
                                ->where('attendance_date', $today)
                                ->firstOrFail();

        if ($attendance->check_out_time) {
            return back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        $attendance->update([
            'check_out_time' => now(),
        ]);

        return back()->with('success', 'Check-out berhasil. ✓');
    }

    /**
     * Admin: View all attendance records
     */
    public function index(Request $request)
    {
        $query = Attendance::query();

        // Filter by employee
        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('attendance_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('attendance_date', '<=', $request->to_date);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $attendances = $query->with('employee')
                            ->orderBy('attendance_date', 'desc')
                            ->paginate(20);

        $employees = Employee::where('is_active', true)->get();

        return view('attendance.index', compact('attendances', 'employees'));
    }

    /**
     * Admin: Update attendance status manually
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:Hadir,Sakit,Izin,Cuti,Alfa,Libur',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return back()->with('success', 'Status absensi berhasil diperbarui.');
    }

    /**
     * Get attendance report per employee
     */
    public function report(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendanceData = Attendance::whereMonth('attendance_date', $month)
                                   ->whereYear('attendance_date', $year)
                                   ->get()
                                   ->groupBy('employee_id');

        $summary = [];
        foreach ($attendanceData as $employeeId => $records) {
            $summary[$employeeId] = [
                'total_days' => $records->count(),
                'hadir' => $records->where('status', 'Hadir')->count(),
                'sakit' => $records->where('status', 'Sakit')->count(),
                'izin' => $records->where('status', 'Izin')->count(),
                'cuti' => $records->where('status', 'Cuti')->count(),
                'alfa' => $records->where('status', 'Alfa')->count(),
                'libur' => $records->where('status', 'Libur')->count(),
            ];
        }

        return view('attendance.report', [
            'summary' => $summary,
            'month' => $month,
            'year' => $year,
        ]);
    }
}
