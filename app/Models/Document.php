<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // ── Status constants ──────────────────────────────────────────────────────

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Mapping status → label & warna Bootstrap untuk tampilan UI.
     */
    const STATUS_MAP = [
        'pending'  => ['label' => 'Menunggu Review', 'color' => 'warning', 'icon' => 'fa-clock'],
        'approved' => ['label' => 'Disetujui',       'color' => 'success', 'icon' => 'fa-circle-check'],
        'rejected' => ['label' => 'Ditolak',         'color' => 'danger',  'icon' => 'fa-circle-xmark'],
        // backward-compat untuk data lama yang masih pakai 'verified'
        'verified' => ['label' => 'Disetujui',       'color' => 'success', 'icon' => 'fa-circle-check'],
    ];

    // ── Relasi ────────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_MAP[$this->status]['color'] ?? 'secondary';
    }

    public function getStatusIconAttribute(): string
    {
        return self::STATUS_MAP[$this->status]['icon'] ?? 'fa-file';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_MAP[$this->status]['label'] ?? ucfirst($this->status);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['approved', 'verified']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeOfType($query, string $documentType)
    {
        return $query->where('document_type', $documentType);
    }
}
