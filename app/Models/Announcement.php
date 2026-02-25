<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'title', 'content', 'priority', 'is_active', 'publish_date', 'expire_date', 'created_by',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'publish_date' => 'date',
        'expire_date'  => 'date',
    ];

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('publish_date')->orWhere('publish_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expire_date')->orWhere('expire_date', '>=', now());
            });
    }

    // ── Accessors ──

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-red-100 text-red-700 border-red-200',
            'high'   => 'bg-amber-100 text-amber-700 border-amber-200',
            'normal' => 'bg-blue-100 text-blue-700 border-blue-200',
            'low'    => 'bg-gray-100 text-gray-700 border-gray-200',
            default  => 'bg-gray-100 text-gray-600 border-gray-200',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'Urgent',
            'high'   => 'Penting',
            'normal' => 'Normal',
            'low'    => 'Rendah',
            default  => $this->priority,
        };
    }

    // ── Relationships ──

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
