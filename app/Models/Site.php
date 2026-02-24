<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'province',
    ];

    /**
     * Get all employees assigned to this site.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
