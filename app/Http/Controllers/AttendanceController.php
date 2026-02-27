<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\Employee;
use App\Models\WorkShift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    /**
     * Daily attendance live dashboard
     */
    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        // Get logged-in user's employee record (for self-service)
        $myEmployee = auth()->user()->employee;
        $myAttendance = null;
        if ($myEmployee) {
            $myEmployee->load('defaultShift');
            $myAttendance = Attendance::where('employee_id', $myEmployee->id)
                ->where('date', $date)
                ->with('shift')
                ->first();
        }

        $employees = auth()->user()->scopedEmployeeQuery()->with([
            'department', 'defaultShift',
            'attendances' => fn($q) => $q->where('date', $date)->with('shift'),
        ])->orderBy('full_name')->get();

        $shifts = WorkShift::active()->orderBy('start_time')->get();

        // Stats
        $totalEmployees = $employees->count();
        $todayAttendances = $employees->map(fn($e) => $e->attendances->first())->filter();
        $stats = [
            'total'   => $totalEmployees,
            'present' => $todayAttendances->whereIn('status', ['present', 'late'])->count(),
            'late'    => $todayAttendances->where('status', 'late')->count(),
            'absent'  => $todayAttendances->where('status', 'absent')->count(),
            'sick'    => $todayAttendances->where('status', 'sick')->count(),
            'leave'   => $todayAttendances->where('status', 'leave')->count(),
            'holiday' => $todayAttendances->where('status', 'holiday')->count(),
            'not_yet' => $totalEmployees - $todayAttendances->count(),
            'checked_out' => $todayAttendances->whereNotNull('check_out')->count(),
        ];

        return view('attendances.index', compact('employees', 'date', 'shifts', 'stats', 'myEmployee', 'myAttendance'));
    }

    /**
     * Check in with photo, GPS, IP
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id'    => 'nullable|exists:work_shifts,id',
            'photo'       => 'nullable|string', // base64 image
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $today = now()->toDateString();

        // Check duplicate
        $existing = Attendance::where('employee_id', $employee->id)->where('date', $today)->first();
        if ($existing) {
            return back()->with('error', 'Karyawan sudah check-in hari ini.');
        }

        // Determine shift
        $shift = null;
        if ($request->shift_id) {
            $shift = WorkShift::find($request->shift_id);
        } elseif ($employee->default_shift_id) {
            $shift = $employee->defaultShift;
        } else {
            $shift = WorkShift::where('is_default', true)->first();
        }

        $now = now()->format('H:i:s');
        $scheduleIn  = $shift ? $shift->start_time : '08:00:00';
        $scheduleOut = $shift ? $shift->end_time : '17:00:00';
        $tolerance   = $shift ? $shift->late_tolerance : 15;

        // Calculate late
        $lateMinutes = Attendance::calculateLateMinutes($now, $scheduleIn, $tolerance);
        $status = $lateMinutes > 0 ? 'late' : 'present';

        // Save photo
        $photoPath = null;
        if ($request->photo) {
            $photoPath = $this->saveBase64Photo($request->photo, 'check-in', $employee->id);
        }

        Attendance::create([
            'employee_id'       => $employee->id,
            'shift_id'          => $shift?->id,
            'date'              => $today,
            'schedule_in'       => $scheduleIn,
            'schedule_out'      => $scheduleOut,
            'check_in'          => $now,
            'late_minutes'      => $lateMinutes,
            'status'            => $status,
            'check_in_photo'    => $photoPath,
            'check_in_ip'       => $request->ip(),
            'lat_in'            => $request->latitude,
            'lng_in'            => $request->longitude,
        ]);

        $message = 'Check-in berhasil pada ' . now()->format('H:i');
        if ($lateMinutes > 0) {
            $message .= " (Terlambat {$lateMinutes} menit)";
        }

        return back()->with('success', $message);
    }

    /**
     * Check out with photo, GPS, IP
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'photo'       => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->where('date', now()->toDateString())
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Karyawan belum check-in hari ini.');
        }
        if ($attendance->check_out) {
            return back()->with('error', 'Karyawan sudah check-out hari ini.');
        }

        $now = now()->format('H:i:s');
        $shift = $attendance->shift;

        // Calculate early leave
        $earlyLeaveMinutes = 0;
        if ($attendance->schedule_out) {
            $out      = Carbon::parse($now);
            $schedule = Carbon::parse($attendance->schedule_out);
            if ($out->lt($schedule)) {
                $earlyLeaveMinutes = (int) $out->diffInMinutes($schedule);
            }
        }

        // Calculate overtime
        $overtimeMinutes = 0;
        $overtimeStatus = 'none';
        if ($attendance->schedule_out) {
            $threshold = $shift ? $shift->overtime_threshold_minutes : 30;
            $overtimeMinutes = Attendance::calculateOvertimeMinutes($now, $attendance->schedule_out, $threshold);
            if ($overtimeMinutes > 0) {
                $overtimeStatus = 'pending'; // needs approval
            }
        }

        // Calculate break minutes
        $breakMinutes = 0;
        if ($shift && $shift->break_start && $shift->break_end) {
            $breakMinutes = Carbon::parse($shift->break_start)->diffInMinutes(Carbon::parse($shift->break_end));
        }

        // Calculate work hours
        $workHours = Attendance::calculateWorkHours($attendance->check_in, $now, $breakMinutes);

        // Save photo
        $photoPath = null;
        if ($request->photo) {
            $photoPath = $this->saveBase64Photo($request->photo, 'check-out', $request->employee_id);
        }

        $attendance->update([
            'check_out'           => $now,
            'early_leave_minutes' => $earlyLeaveMinutes,
            'work_hours_decimal'  => $workHours,
            'overtime_minutes'    => $overtimeMinutes,
            'overtime_status'     => $overtimeStatus,
            'check_out_photo'     => $photoPath,
            'check_out_ip'        => $request->ip(),
            'lat_out'             => $request->latitude,
            'lng_out'             => $request->longitude,
        ]);

        $message = 'Check-out berhasil pada ' . now()->format('H:i');
        if ($overtimeMinutes > 0) {
            $h = intdiv($overtimeMinutes, 60);
            $m = $overtimeMinutes % 60;
            $otLabel = $h > 0 ? "{$h}j {$m}m" : "{$m}m";
            $message .= " (Lembur: {$otLabel} — menunggu approval)";
        }

        return back()->with('success', $message);
    }

    /**
     * Update attendance status manually
     */
    public function updateStatus(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:present,late,absent,sick,leave,holiday',
            'notes'  => 'nullable|string|max:500',
        ]);

        $attendance->update($request->only('status', 'notes'));
        return back()->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    /**
     * Bulk mark (holidays, etc.)
     */
    public function bulkMark(Request $request)
    {
        $request->validate([
            'date'     => 'required|date',
            'status'   => 'required|in:present,late,absent,sick,leave,holiday',
            'shift_id' => 'nullable|exists:work_shifts,id',
        ]);

        $shift = null;
        if ($request->shift_id) {
            $shift = WorkShift::find($request->shift_id);
        } else {
            $shift = WorkShift::where('is_default', true)->first();
        }

        $employees = auth()->user()->scopedEmployeeQuery()->pluck('id');

        foreach ($employees as $empId) {
            Attendance::updateOrCreate(
                ['employee_id' => $empId, 'date' => $request->date],
                [
                    'status'       => $request->status,
                    'shift_id'     => $shift?->id,
                    'schedule_in'  => $shift?->start_time,
                    'schedule_out' => $shift?->end_time,
                ]
            );
        }

        return back()->with('success', "Absensi massal berhasil untuk {$employees->count()} karyawan.");
    }

    /**
     * Monthly recap with detailed stats
     */
    public function recap(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $parts = explode('-', $month);
        $year  = (int) $parts[0];
        $mon   = (int) $parts[1];

        $startDate = Carbon::createFromDate($year, $mon, 1)->startOfMonth();
        $endDate   = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        // Filter by employee or department
        $query = auth()->user()->scopedEmployeeQuery()->with([
            'department',
            'attendances' => fn($q) => $q->whereBetween('date', [$startDate, $endDate]),
        ]);

        if ($request->filled('employee_id')) {
            $query->where('id', $request->employee_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $employees = $query->orderBy('full_name')->get();

        $recap = $employees->map(function ($emp) use ($daysInMonth) {
            $attendances = $emp->attendances;
            $totalLate = $attendances->sum('late_minutes');
            $totalOvertime = $attendances->where('overtime_status', 'approved')->sum('overtime_minutes');
            $totalWorkHours = $attendances->sum('work_hours_decimal');
            $presentDays = $attendances->whereIn('status', ['present', 'late'])->count();

            return [
                'employee'        => $emp,
                'present'         => $presentDays,
                'late'            => $attendances->where('status', 'late')->count(),
                'absent'          => $attendances->where('status', 'absent')->count(),
                'sick'            => $attendances->where('status', 'sick')->count(),
                'leave'           => $attendances->where('status', 'leave')->count(),
                'holiday'         => $attendances->where('status', 'holiday')->count(),
                'total_days'      => $daysInMonth,
                'total_late_mins' => $totalLate,
                'total_overtime'  => $totalOvertime,
                'total_work_hours'=> round($totalWorkHours, 1),
                'attendance_pct'  => $daysInMonth > 0 ? round(($presentDays / $daysInMonth) * 100) : 0,
            ];
        });

        $departments = \App\Models\Department::orderBy('name')->get();
        $allEmployees = auth()->user()->scopedEmployeeQuery()->orderBy('full_name')->get();

        return view('attendances.recap', compact(
            'recap', 'month', 'daysInMonth', 'departments', 'allEmployees'
        ));
    }

    /**
     * Overtime management — list & approve/reject
     */
    public function overtime(Request $request)
    {
        $status = $request->input('status', 'pending');

        $user = auth()->user();
        $query = Attendance::with(['employee.department', 'shift', 'overtimeApprover'])
            ->where('overtime_minutes', '>', 0);

        if (!$user->isAdmin()) {
            $query->whereHas('employee', fn($q) => $q->where('site_id', $user->site_id));
        }

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('overtime_status', $status);
        }

        $overtimes = $query->latest('date')->paginate(20);
        $pendingCount = auth()->user()->scopedAttendanceQuery()->where('overtime_status', 'pending')->count();

        return view('attendances.overtime', compact('overtimes', 'status', 'pendingCount'));
    }

    /**
     * Approve overtime
     */
    public function approveOvertime(Attendance $attendance)
    {
        auth()->user()->authorizeSiteAccess($attendance);

        if ($attendance->overtime_status !== 'pending') {
            return back()->with('error', 'Lembur ini sudah diproses.');
        }

        $attendance->update([
            'overtime_status'      => 'approved',
            'overtime_approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Lembur berhasil disetujui.');
    }

    /**
     * Reject overtime
     */
    public function rejectOvertime(Attendance $attendance)
    {
        auth()->user()->authorizeSiteAccess($attendance);

        if ($attendance->overtime_status !== 'pending') {
            return back()->with('error', 'Lembur ini sudah diproses.');
        }

        $attendance->update([
            'overtime_status'      => 'rejected',
            'overtime_approved_by' => auth()->id(),
            'overtime_minutes'     => 0,
        ]);

        return back()->with('success', 'Lembur berhasil ditolak.');
    }

    /**
     * Attendance corrections — list
     */
    public function corrections(Request $request)
    {
        $user = auth()->user();
        $query = AttendanceCorrection::with([
            'attendance.employee.department',
            'requester', 'approver',
        ])->latest();

        if (!$user->isAdmin()) {
            $query->whereHas('attendance.employee', fn($q) => $q->where('site_id', $user->site_id));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $corrections = $query->paginate(20);
        $pendingCount = AttendanceCorrection::where('status', 'pending')
            ->when(!auth()->user()->isAdmin(), fn($q) => $q->whereHas('attendance.employee', fn($sq) => $sq->where('site_id', auth()->user()->site_id)))
            ->count();

        return view('attendances.corrections', compact('corrections', 'pendingCount'));
    }

    /**
     * Store a correction request
     */
    public function storeCorrection(Request $request)
    {
        $validated = $request->validate([
            'attendance_id'     => 'required|exists:attendances,id',
            'corrected_check_in'  => 'nullable|date_format:H:i',
            'corrected_check_out' => 'nullable|date_format:H:i',
            'corrected_status'    => 'nullable|in:present,late,absent,sick,leave,holiday',
            'reason'              => 'required|string|max:1000',
        ]);

        $attendance = Attendance::findOrFail($validated['attendance_id']);
        auth()->user()->authorizeSiteAccess($attendance);

        AttendanceCorrection::create([
            'attendance_id'       => $attendance->id,
            'requested_by'        => auth()->id(),
            'original_check_in'   => $attendance->check_in,
            'original_check_out'  => $attendance->check_out,
            'corrected_check_in'  => $validated['corrected_check_in'] ?? null,
            'corrected_check_out' => $validated['corrected_check_out'] ?? null,
            'corrected_status'    => $validated['corrected_status'] ?? null,
            'reason'              => $validated['reason'],
        ]);

        return back()->with('success', 'Pengajuan koreksi berhasil dikirim.');
    }

    /**
     * Approve correction
     */
    public function approveCorrection(AttendanceCorrection $correction)
    {
        auth()->user()->authorizeSiteAccess($correction->attendance);

        if ($correction->status !== 'pending') {
            return back()->with('error', 'Koreksi ini sudah diproses.');
        }

        $attendance = $correction->attendance;

        // Apply corrections
        $updateData = [];
        if ($correction->corrected_check_in) {
            $updateData['check_in'] = $correction->corrected_check_in;
        }
        if ($correction->corrected_check_out) {
            $updateData['check_out'] = $correction->corrected_check_out;
        }
        if ($correction->corrected_status) {
            $updateData['status'] = $correction->corrected_status;
        }

        // Recalculate if times were corrected
        if (isset($updateData['check_in']) || isset($updateData['check_out'])) {
            $checkIn  = $updateData['check_in'] ?? $attendance->check_in;
            $checkOut = $updateData['check_out'] ?? $attendance->check_out;

            if ($checkIn && $attendance->schedule_in) {
                $shift = $attendance->shift;
                $tolerance = $shift ? $shift->late_tolerance : 15;
                $updateData['late_minutes'] = Attendance::calculateLateMinutes($checkIn, $attendance->schedule_in, $tolerance);
                $updateData['status'] = $updateData['late_minutes'] > 0 ? 'late' : ($updateData['status'] ?? 'present');
            }

            if ($checkIn && $checkOut) {
                $breakMinutes = 0;
                if ($attendance->shift && $attendance->shift->break_start) {
                    $breakMinutes = Carbon::parse($attendance->shift->break_start)
                        ->diffInMinutes(Carbon::parse($attendance->shift->break_end));
                }
                $updateData['work_hours_decimal'] = Attendance::calculateWorkHours($checkIn, $checkOut, $breakMinutes);
            }
        }

        if (!empty($updateData)) {
            $attendance->update($updateData);
        }

        $correction->update([
            'status'       => 'approved',
            'approved_by'  => auth()->id(),
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Koreksi berhasil disetujui dan data diperbarui.');
    }

    /**
     * Reject correction
     */
    public function rejectCorrection(Request $request, AttendanceCorrection $correction)
    {
        auth()->user()->authorizeSiteAccess($correction->attendance);

        if ($correction->status !== 'pending') {
            return back()->with('error', 'Koreksi ini sudah diproses.');
        }

        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $correction->update([
            'status'           => 'rejected',
            'approved_by'      => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
            'responded_at'     => now(),
        ]);

        return back()->with('success', 'Koreksi berhasil ditolak.');
    }

    /**
     * Work shifts management
     */
    public function shifts()
    {
        $shifts = WorkShift::withCount('employees')->orderBy('start_time')->get();
        return view('attendances.shifts', compact('shifts'));
    }

    /**
     * Store a new shift
     */
    public function storeShift(Request $request)
    {
        $validated = $request->validate([
            'name'                       => 'required|string|max:100',
            'code'                       => 'required|string|max:10|unique:work_shifts,code',
            'start_time'                 => 'required|date_format:H:i',
            'end_time'                   => 'required|date_format:H:i',
            'break_start'                => 'nullable|date_format:H:i',
            'break_end'                  => 'nullable|date_format:H:i',
            'late_tolerance'             => 'required|integer|min:0|max:120',
            'minimum_work_minutes'       => 'required|integer|min:60|max:720',
            'overtime_threshold_minutes' => 'required|integer|min:0|max:120',
        ]);

        WorkShift::create($validated);
        return back()->with('success', 'Shift berhasil ditambahkan.');
    }

    /**
     * Update shift
     */
    public function updateShift(Request $request, WorkShift $shift)
    {
        $validated = $request->validate([
            'name'                       => 'required|string|max:100',
            'code'                       => 'required|string|max:10|unique:work_shifts,code,' . $shift->id,
            'start_time'                 => 'required|date_format:H:i',
            'end_time'                   => 'required|date_format:H:i',
            'break_start'                => 'nullable|date_format:H:i',
            'break_end'                  => 'nullable|date_format:H:i',
            'late_tolerance'             => 'required|integer|min:0|max:120',
            'minimum_work_minutes'       => 'required|integer|min:60|max:720',
            'overtime_threshold_minutes' => 'required|integer|min:0|max:120',
            'is_active'                  => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $shift->update($validated);
        return back()->with('success', 'Shift berhasil diperbarui.');
    }

    /**
     * Set a shift as default
     */
    public function setDefaultShift(WorkShift $shift)
    {
        WorkShift::where('is_default', true)->update(['is_default' => false]);
        $shift->update(['is_default' => true]);
        return back()->with('success', "Shift \"{$shift->name}\" dijadikan default.");
    }

    /**
     * Save base64 photo to storage
     */
    private function saveBase64Photo(string $base64, string $type, int $employeeId): ?string
    {
        try {
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
            $imageData = base64_decode($imageData);

            if (!$imageData) return null;

            $filename = "attendance/{$type}/" . now()->format('Y-m-d') . "/{$employeeId}_" . now()->format('His') . '.jpg';
            Storage::disk('public')->put($filename, $imageData);

            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
}
