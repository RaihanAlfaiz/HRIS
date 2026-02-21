<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'nik_ktp',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'religion',
        'marital_status',
        'blood_type',
        'address_ktp',
        'address_domicile',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Get the employee that owns this profile.
     * DBML: employee_id bigint [ref: - employees.id, unique] (1-to-1 inverse)
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
