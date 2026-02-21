<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'department_id',
        'nip',
        'full_name',
        'position',
        'employment_status',
        'join_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'join_date' => 'date',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    /**
     * Get the department this employee belongs to.
     * DBML: department_id bigint [ref: > departments.id]
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the employee's personal profile (1-to-1).
     * DBML: employee_id bigint [ref: - employees.id, unique]
     */
    public function profile(): HasOne
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    /**
     * Get the employee's contact information (1-to-1).
     * DBML: employee_id bigint [ref: - employees.id, unique]
     */
    public function contact(): HasOne
    {
        return $this->hasOne(EmployeeContact::class);
    }

    /**
     * Get the employee's documents (1-to-Many).
     * DBML: employee_id bigint [ref: > employees.id]
     */
    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    /**
     * Get the employee's financial information (1-to-1).
     * DBML: employee_id bigint [ref: - employees.id, unique]
     */
    public function financial(): HasOne
    {
        return $this->hasOne(EmployeeFinancial::class);
    }
}
