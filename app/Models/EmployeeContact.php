<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeContact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'email_work',
        'email_personal',
        'phone_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
    ];

    /**
     * Get the employee that owns this contact info.
     * DBML: employee_id bigint [ref: - employees.id, unique] (1-to-1 inverse)
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
