<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'form_data' => 'array',
    ];

    // ── Status: dokumen workflow ───────────────────────────────────────────────

    /** Status order untuk alur dokumen */
    const DOC_STATUS_DRAFT               = 'draft';
    const DOC_STATUS_WAITING_VERIFICATION = 'waiting_verification';
    const DOC_STATUS_REVISION            = 'revision';
    const DOC_STATUS_VERIFIED            = 'verified';

    /** Status order legacy (dipertahankan agar tidak break UI lama) */
    const DOC_STATUS_PENDING    = 'pending';
    const DOC_STATUS_APPROVED   = 'approved';
    const DOC_STATUS_PROCESSING = 'processing';
    const DOC_STATUS_COMPLETED  = 'completed';
    const DOC_STATUS_CANCELLED  = 'cancelled';
    const DOC_STATUS_REJECTED   = 'rejected';

    const STATUS_MAP = [
        'draft'               => ['label' => 'Dokumen Belum Lengkap',      'color' => 'secondary'],
        'waiting_verification'=> ['label' => 'Menunggu Verifikasi',        'color' => 'warning'],
        'revision'            => ['label' => 'Perlu Revisi Dokumen',       'color' => 'danger'],
        'verified'            => ['label' => 'Dokumen Terverifikasi',      'color' => 'success'],
        // Legacy
        'pending'             => ['label' => 'Menunggu',                   'color' => 'warning'],
        'approved'            => ['label' => 'Disetujui',                  'color' => 'success'],
        'processing'          => ['label' => 'Diproses',                   'color' => 'info'],
        'completed'           => ['label' => 'Selesai',                    'color' => 'primary'],
        'cancelled'           => ['label' => 'Dibatalkan',                 'color' => 'secondary'],
        'rejected'            => ['label' => 'Ditolak',                    'color' => 'danger'],
    ];

    // ── Payment statuses ──────────────────────────────────────────────────────

    const PAYMENT_STATUSES = [
        'unpaid'               => ['label' => 'Belum Bayar',               'color' => 'secondary'],
        'pending_verification' => ['label' => 'Menunggu Verifikasi',       'color' => 'warning'],
        'verified'             => ['label' => 'Pembayaran Terverifikasi',  'color' => 'success'],
        'rejected'             => ['label' => 'Pembayaran Ditolak',        'color' => 'danger'],
    ];

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status]['label'] ?? ucfirst($this->payment_status);
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status]['color'] ?? 'secondary';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_MAP[$this->status]['label'] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_MAP[$this->status]['color'] ?? 'secondary';
    }

    // ── Relasi ────────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function roomBenefit()
    {
        return $this->hasOne(RoomBenefit::class);
    }

    public function mailNotifications()
    {
        return $this->hasMany(VirtualOfficeMailNotification::class, 'virtual_office_id');
    }

    public function guestNotifications()
    {
        return $this->hasMany(VirtualOfficeGuestNotification::class, 'virtual_office_id');
    }

    /** All benefit records for this order (meeting + podcast as separate records) */
    public function roomBenefits()
    {
        return $this->hasMany(RoomBenefit::class);
    }
}
