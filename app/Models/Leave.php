<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    protected $fillable = [
        'employee_id', 'type', 'start_date', 'end_date', 'days',
        'reason', 'status', 'approved_by', 'rejection_reason', 'responded_at',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'responded_at' => 'datetime',
    ];

    // ── Accessors ──

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'cuti_tahunan'    => 'Cuti Tahunan',
            'cuti_sakit'      => 'Cuti Sakit',
            'cuti_melahirkan' => 'Cuti Melahirkan',
            'cuti_menikah'    => 'Cuti Menikah',
            'cuti_khusus'     => 'Cuti Khusus',
            'izin'            => 'Izin',
            default           => $this->type,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'bg-amber-100 text-amber-700',
            'approved' => 'bg-emerald-100 text-emerald-700',
            'rejected' => 'bg-red-100 text-red-700',
            default    => 'bg-gray-100 text-gray-600',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => $this->status,
        };
    }

    // ── Relationships ──

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
