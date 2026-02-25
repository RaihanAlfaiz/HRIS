<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id', 'period', 'basic_salary',
        'transport_allowance', 'meal_allowance', 'other_allowance', 'overtime',
        'bpjs_deduction', 'tax_deduction', 'other_deduction',
        'total_earning', 'total_deduction', 'net_salary', 'notes',
    ];

    protected $casts = [
        'basic_salary'        => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'meal_allowance'      => 'decimal:2',
        'other_allowance'     => 'decimal:2',
        'overtime'            => 'decimal:2',
        'bpjs_deduction'      => 'decimal:2',
        'tax_deduction'       => 'decimal:2',
        'other_deduction'     => 'decimal:2',
        'total_earning'       => 'decimal:2',
        'total_deduction'     => 'decimal:2',
        'net_salary'          => 'decimal:2',
    ];

    // ── Helpers ──

    public function calculateTotals(): void
    {
        $this->total_earning   = $this->basic_salary + $this->transport_allowance + $this->meal_allowance + $this->other_allowance + $this->overtime;
        $this->total_deduction = $this->bpjs_deduction + $this->tax_deduction + $this->other_deduction;
        $this->net_salary      = $this->total_earning - $this->total_deduction;
    }

    public function getPeriodLabelAttribute(): string
    {
        $parts = explode('-', $this->period);
        if (count($parts) === 2) {
            $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return ($months[(int)$parts[1]] ?? $parts[1]) . ' ' . $parts[0];
        }
        return $this->period;
    }

    // ── Relationships ──

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
