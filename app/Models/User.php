<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    protected $fillable = [
        'username',
        'name',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ── Role helpers ──

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHr(): bool
    {
        return $this->role === 'hr';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    /**
     * Check if user has at least the given role level.
     * admin > hr > viewer
     */
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
