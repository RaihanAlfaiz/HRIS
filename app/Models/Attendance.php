<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id', 'shift_id', 'date',
        'schedule_in', 'schedule_out',
        'check_in', 'check_out',
        'late_minutes', 'early_leave_minutes', 'work_hours_decimal',
        'overtime_minutes', 'overtime_status', 'overtime_approved_by',
        'status', 'notes', 'work_from',
        'check_in_photo', 'check_out_photo',
        'check_in_ip', 'check_out_ip',
        'lat_in', 'lng_in', 'lat_out', 'lng_out',
    ];

    protected $casts = [
        'date'               => 'date',
        'work_hours_decimal' => 'decimal:2',
        'lat_in'             => 'decimal:7',
        'lng_in'             => 'decimal:7',
        'lat_out'            => 'decimal:7',
        'lng_out'            => 'decimal:7',
    ];

    // ── Accessors ──

    /**
     * Formatted work hours like "8j 30m"
     */
    public function getWorkHoursAttribute(): ?string
    {
        if (!$this->check_in || !$this->check_out) return null;

        $in  = Carbon::parse($this->check_in);
        $out = Carbon::parse($this->check_out);
        $totalMinutes = $in->diffInMinutes($out);

        // Subtract break time if applicable
        if ($this->shift && $this->shift->break_start && $this->shift->break_end) {
            $breakStart = Carbon::parse($this->shift->break_start);
            $breakEnd   = Carbon::parse($this->shift->break_end);
            $breakMinutes = $breakStart->diffInMinutes($breakEnd);

            // Only subtract break if work period spans break
            $checkInTime  = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->check_in);
            $checkOutTime = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->check_out);
            $breakStartFull = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->shift->break_start);
            $breakEndFull   = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->shift->break_end);

            if ($checkInTime->lt($breakEndFull) && $checkOutTime->gt($breakStartFull)) {
                $totalMinutes -= $breakMinutes;
            }
        }

        $hours = intdiv(max(0, $totalMinutes), 60);
        $mins  = max(0, $totalMinutes) % 60;

        return $mins > 0 ? "{$hours}j {$mins}m" : "{$hours}j 0m";
    }

    /**
     * Late duration formatted
     */
    public function getLateFormattedAttribute(): ?string
    {
        if ($this->late_minutes <= 0) return null;
        $h = intdiv($this->late_minutes, 60);
        $m = $this->late_minutes % 60;
        return $h > 0 ? "{$h}j {$m}m" : "{$m} menit";
    }

    /**
     * Overtime duration formatted
     */
    public function getOvertimeFormattedAttribute(): ?string
    {
        if ($this->overtime_minutes <= 0) return null;
        $h = intdiv($this->overtime_minutes, 60);
        $m = $this->overtime_minutes % 60;
        return $h > 0 ? "{$h}j {$m}m" : "{$m} menit";
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'present' => 'Hadir',
            'late'    => 'Terlambat',
            'absent'  => 'Alpa',
            'sick'    => 'Sakit',
            'leave'   => 'Cuti',
            'holiday' => 'Libur',
            default   => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'present' => 'bg-emerald-100 text-emerald-700',
            'late'    => 'bg-amber-100 text-amber-700',
            'absent'  => 'bg-red-100 text-red-700',
            'sick'    => 'bg-orange-100 text-orange-700',
            'leave'   => 'bg-blue-100 text-blue-700',
            'holiday' => 'bg-gray-100 text-gray-700',
            default   => 'bg-gray-100 text-gray-600',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'present' => '✓',
            'late'    => '⏰',
            'absent'  => '✗',
            'sick'    => '🏥',
            'leave'   => '🏖',
            'holiday' => '🎉',
            default   => '—',
        };
    }

    public function getOvertimeStatusLabelAttribute(): string
    {
        return match ($this->overtime_status) {
            'none'     => '—',
            'pending'  => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => $this->overtime_status,
        };
    }

    public function getOvertimeStatusColorAttribute(): string
    {
        return match ($this->overtime_status) {
            'pending'  => 'bg-amber-100 text-amber-700',
            'approved' => 'bg-emerald-100 text-emerald-700',
            'rejected' => 'bg-red-100 text-red-700',
            default    => '',
        };
    }

    /**
     * Check if this attendance has GPS coordinates
     */
    public function getHasLocationAttribute(): bool
    {
        return $this->lat_in !== null && $this->lng_in !== null;
    }

    // ── Static Helpers ──

    /**
     * Calculate late minutes based on check-in time and shift schedule
     */
    public static function calculateLateMinutes(string $checkInTime, string $scheduleIn, int $tolerance = 0): int
    {
        $in       = Carbon::parse($checkInTime);
        $schedule = Carbon::parse($scheduleIn)->addMinutes($tolerance);

        if ($in->gt($schedule)) {
            return (int) $schedule->diffInMinutes($in);
        }

        return 0;
    }

    /**
     * Calculate overtime minutes
     */
    public static function calculateOvertimeMinutes(
        string $checkOutTime,
        string $scheduleOut,
        int $threshold = 30
    ): int {
        $out      = Carbon::parse($checkOutTime);
        $schedule = Carbon::parse($scheduleOut);

        if ($out->gt($schedule)) {
            $overtime = (int) $schedule->diffInMinutes($out);
            return $overtime >= $threshold ? $overtime : 0;
        }

        return 0;
    }

    /**
     * Calculate work hours in decimal
     */
    public static function calculateWorkHours(string $checkIn, string $checkOut, ?int $breakMinutes = null): float
    {
        $in  = Carbon::parse($checkIn);
        $out = Carbon::parse($checkOut);
        $totalMinutes = $in->diffInMinutes($out);

        if ($breakMinutes && $breakMinutes > 0) {
            $totalMinutes -= $breakMinutes;
        }

        return round(max(0, $totalMinutes) / 60, 2);
    }

    /**
     * Determine status based on check-in time
     */
    public static function determineStatus(string $checkInTime, string $scheduleIn, int $tolerance = 0): string
    {
        $lateMinutes = self::calculateLateMinutes($checkInTime, $scheduleIn, $tolerance);
        return $lateMinutes > 0 ? 'late' : 'present';
    }

    // ── Relationships ──

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(WorkShift::class, 'shift_id');
    }

    public function overtimeApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'overtime_approved_by');
    }

    public function corrections(): HasMany
    {
        return $this->hasMany(AttendanceCorrection::class);
    }
}
