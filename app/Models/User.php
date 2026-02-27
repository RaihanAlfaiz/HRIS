<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'site_id',
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

    /**
     * Check if user needs a site assignment to access the app.
     * Admin can access without site (sees all sites).
     */
    public function needsSiteAssignment(): bool
    {
        return !$this->isAdmin() && !$this->site_id;
    }

    /**
     * Throw 403 Forbidden if user tries to access a model outside their assigned site.
     * Admin has full access.
     */
    public function authorizeSiteAccess($model)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($model instanceof \App\Models\Employee) {
            if ($this->isViewer()) {
                abort_if($model->id !== $this->employee?->id, 403, 'Akses ditolak: Anda hanya dapat mengakses data Anda sendiri.');
            } else {
                abort_if($model->site_id !== $this->site_id, 403, 'Akses ditolak: Anda tidak memiliki akses ke data di site ini.');
            }
        } elseif (isset($model->employee)) {
            // For related models: Attendance, Leave, Payroll, Document, Contract, etc.
            if ($this->isViewer()) {
                abort_if($model->employee_id !== $this->employee?->id, 403, 'Akses ditolak: Anda hanya dapat mengakses data Anda sendiri.');
            } else {
                abort_if($model->employee->site_id !== $this->site_id, 403, 'Akses ditolak: Anda tidak memiliki akses ke data di site ini.');
            }
        }

        return true;
    }

    // ── Site scoping helpers ──

    /**
     * Get a base Employee query scoped to the user's site.
     * Admin: sees all employees.
     * HR/Viewer: sees only employees in their site.
     */
    public function scopedEmployeeQuery(): Builder
    {
        $query = Employee::query();

        if ($this->isAdmin()) {
            return $query;
        }

        if ($this->isHr()) {
            return $query->where('site_id', $this->site_id);
        }

        // Viewer (employee) only sees themselves
        return $query->where('id', $this->employee?->id ?? -1);
    }

    /**
     * Get a base Attendance query scoped to the user's site.
     */
    public function scopedAttendanceQuery(): Builder
    {
        $query = Attendance::query();

        if ($this->isAdmin()) {
            return $query;
        }

        if ($this->isHr()) {
            return $query->whereHas('employee', fn($q) => $q->where('site_id', $this->site_id));
        }

        return $query->where('employee_id', $this->employee?->id ?? -1);
    }

    /**
     * Get a base Leave query scoped to the user's site.
     */
    public function scopedLeaveQuery(): Builder
    {
        $query = Leave::query();

        if ($this->isAdmin()) {
            return $query;
        }

        if ($this->isHr()) {
            return $query->whereHas('employee', fn($q) => $q->where('site_id', $this->site_id));
        }

        return $query->where('employee_id', $this->employee?->id ?? -1);
    }

    /**
     * Get a base Payroll query scoped to the user's site.
     */
    public function scopedPayrollQuery(): Builder
    {
        $query = Payroll::query();

        if ($this->isAdmin()) {
            return $query;
        }

        if ($this->isHr()) {
            return $query->whereHas('employee', fn($q) => $q->where('site_id', $this->site_id));
        }

        return $query->where('employee_id', $this->employee?->id ?? -1);
    }

    // ── Relationships ──

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
