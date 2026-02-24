<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'site_id',
        'nip',
        'full_name',
        'position',
        'employment_status',
        'join_date',
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
        ];
    }

    // ──────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────

    /**
     * Get employee tenure (lama bekerja) — auto-calculated from join_date.
     * Returns human-readable string like "2 tahun 3 bulan".
     */
    public function getTenureAttribute(): string
    {
        $joinDate = $this->join_date;
        if (!$joinDate) {
            return '—';
        }

        $now = Carbon::now();
        $years  = (int) $joinDate->diffInYears($now);
        $months = (int) $joinDate->copy()->addYears($years)->diffInMonths($now);
        $days   = (int) $joinDate->copy()->addYears($years)->addMonths($months)->diffInDays($now);

        $parts = [];
        if ($years > 0) {
            $parts[] = "{$years} tahun";
        }
        if ($months > 0) {
            $parts[] = "{$months} bulan";
        }
        if (empty($parts) || ($years === 0 && $months === 0)) {
            $parts[] = "{$days} hari";
        }

        return implode(' ', $parts);
    }

    /**
     * Get tenure in total months (for sorting/comparison).
     */
    public function getTenureMonthsAttribute(): int
    {
        return $this->join_date ? (int) $this->join_date->diffInMonths(now()) : 0;
    }

    /**
     * Get the active (latest) contract for this employee.
     */
    public function getActiveContractAttribute(): ?EmployeeContract
    {
        return $this->contracts()->orderByDesc('end_date')->first();
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    public function contact(): HasOne
    {
        return $this->hasOne(EmployeeContact::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function financial(): HasOne
    {
        return $this->hasOne(EmployeeFinancial::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class);
    }

    public function kpis(): HasMany
    {
        return $this->hasMany(EmployeeKpi::class);
    }
}
