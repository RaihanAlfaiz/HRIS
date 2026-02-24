<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeKpi extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period',
        'score',
        'rating',
        'notes',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
        ];
    }

    /**
     * Get the employee for this KPI.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get rating color/badge class based on rating value.
     */
    public function getRatingColorAttribute(): string
    {
        return match ($this->rating) {
            'Excellent'     => 'bg-emerald-100 text-emerald-700',
            'Good'          => 'bg-blue-100 text-blue-700',
            'Average'       => 'bg-amber-100 text-amber-700',
            'Below Average' => 'bg-orange-100 text-orange-700',
            'Poor'          => 'bg-red-100 text-red-700',
            default         => 'bg-gray-100 text-gray-700',
        };
    }
}
