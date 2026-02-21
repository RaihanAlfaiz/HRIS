<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeFinancial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'npwp',
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'bank_name',
        'bank_account_number',
    ];

    /**
     * Get the employee that owns this financial record.
     * DBML: employee_id bigint [ref: - employees.id, unique] (1-to-1 inverse)
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
