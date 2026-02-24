<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'contract_number',
        'contract_type',
        'start_date',
        'end_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    /**
     * Get the employee for this contract.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get remaining days until contract ends.
     */
    public function getRemainingDaysAttribute(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->end_date, false);
    }

    /**
     * Check if contract is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date->isPast();
    }

    /**
     * Check if contract is expiring soon (within 30 days).
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        return !$this->is_expired && $this->remaining_days <= 30;
    }
}
