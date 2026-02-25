<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeHistory extends Model
{
    protected $fillable = [
        'employee_id', 'type', 'field_changed', 'old_value', 'new_value', 'description', 'changed_by',
    ];

    // ── Accessors ──

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'promotion'         => 'Promosi',
            'department_change' => 'Pindah Departemen',
            'status_change'     => 'Perubahan Status',
            'position_change'   => 'Perubahan Jabatan',
            'site_change'       => 'Pindah Site',
            'salary_change'     => 'Perubahan Gaji',
            'other'             => 'Lainnya',
            default             => $this->type,
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'promotion'         => 'bg-emerald-100 text-emerald-700',
            'department_change' => 'bg-blue-100 text-blue-700',
            'status_change'     => 'bg-amber-100 text-amber-700',
            'position_change'   => 'bg-violet-100 text-violet-700',
            'site_change'       => 'bg-cyan-100 text-cyan-700',
            'salary_change'     => 'bg-orange-100 text-orange-700',
            default             => 'bg-gray-100 text-gray-700',
        };
    }

    // ── Relationships ──

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
