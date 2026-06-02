<!-- Employee Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Today's Attendance -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Absensi Hari Ini</h3>
        @if($today_attendance)
            <div class="space-y-2">
                <p class="text-sm text-gray-600">Status: <span class="font-bold text-green-600">{{ $today_attendance->status }}</span></p>
                @if($today_attendance->check_in_time)
                    <p class="text-sm text-gray-600">Check-in: <span class="font-bold">{{ $today_attendance->check_in_time->format('H:i') }}</span></p>
                @endif
                @if($today_attendance->check_out_time)
                    <p class="text-sm text-gray-600">Check-out: <span class="font-bold">{{ $today_attendance->check_out_time->format('H:i') }}</span></p>
                @endif
            </div>
        @else
            <p class="text-gray-600 text-sm">Belum ada data absensi hari ini</p>
        @endif
        <form action="{{ route('attendance.checkin') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                📍 Check-In Sekarang
            </button>
        </form>
    </div>

    <!-- Leave Balance -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Saldo Cuti {{ now()->year }}</h3>
        @if($leave_balance)
            <div class="space-y-3">
                <div class="flex justify-between items-center pb-2 border-b">
                    <span class="text-gray-600 text-sm">Cuti Tahunan</span>
                    <span class="font-bold">{{ $leave_balance->annual_leave_balance - $leave_balance->annual_leave_used }} / {{ $leave_balance->annual_leave_balance }} hari</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b">
                    <span class="text-gray-600 text-sm">Sakit</span>
                    <span class="font-bold">{{ $leave_balance->sick_leave_balance - $leave_balance->sick_leave_used }} / {{ $leave_balance->sick_leave_balance }} hari</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Khusus</span>
                    <span class="font-bold">{{ $leave_balance->special_leave_balance - $leave_balance->special_leave_used }} / {{ $leave_balance->special_leave_balance }} hari</span>
                </div>
            </div>
        @else
            <p class="text-gray-600 text-sm">Data cuti tidak tersedia</p>
        @endif
    </div>

    <!-- Pending Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Permintaan Cuti</h3>
        <p class="text-gray-600 text-sm mb-4">
            Menunggu Persetujuan: <span class="font-bold text-yellow-600">{{ $pending_leave_requests }}</span>
        </p>
        <a href="#" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">
            📋 Ajukan Cuti
        </a>
    </div>
</div>

<!-- Recent Salary Slips -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Slip Gaji Terbaru</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-700">Bulan</th>
                    <th class="px-4 py-2 text-left text-gray-700">Gaji Kotor</th>
                    <th class="px-4 py-2 text-left text-gray-700">Potongan</th>
                    <th class="px-4 py-2 text-left text-gray-700">Gaji Bersih</th>
                    <th class="px-4 py-2 text-left text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_payroll as $payroll)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ now()->setMonth($payroll->month)->format('F Y') }}</td>
                        <td class="px-4 py-2 font-semibold">Rp {{ number_format($payroll->gross_salary, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 font-bold text-green-600">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-xs font-medium">📥 Download</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-600">Belum ada slip gaji</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Profile Card -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Profil Saya</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-gray-600 text-sm">NIP</p>
            <p class="font-semibold text-gray-900">{{ $employee->nip }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Jabatan</p>
            <p class="font-semibold text-gray-900">{{ $employee->position->name ?? '-' }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Departemen</p>
            <p class="font-semibold text-gray-900">{{ $employee->department->name ?? '-' }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Tanggal Masuk</p>
            <p class="font-semibold text-gray-900">{{ $employee->join_date->format('d F Y') }}</p>
        </div>
    </div>
    <a href="{{ route('my-profile') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
        ✏️ Edit Profil
    </a>
</div>
