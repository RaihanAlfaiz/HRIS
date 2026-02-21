<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'document_type',
        'file_path',
        'url_link',
    ];

    /**
     * Get the employee that owns this document.
     * DBML: employee_id bigint [ref: > employees.id] (Many-to-One inverse)
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
