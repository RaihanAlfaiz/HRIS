<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkShift extends Model
{
    protected $fillable = [
        'name', 'code', 'start_time', 'end_time',
        'break_start', 'break_end',
        'late_tolerance', 'early_leave_tolerance',
        'minimum_work_minutes', 'overtime_threshold_minutes',
        'is_default', 'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];

    // ── Accessors ──

    public function getFormattedScheduleAttribute(): string
    {
        return substr($this->start_time, 0, 5) . ' — ' . substr($this->end_time, 0, 5);
    }

    public function getMinimumWorkHoursAttribute(): string
    {
        $hours = intdiv($this->minimum_work_minutes, 60);
        $mins  = $this->minimum_work_minutes % 60;
        return $mins > 0 ? "{$hours}j {$mins}m" : "{$hours} jam";
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // ── Relationships ──

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'shift_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'default_shift_id');
    }
}
