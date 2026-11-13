<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'form_data'        => 'array',
        'transaction_date' => 'date',
        'submitted_at'     => 'datetime',
        'approved_at'      => 'datetime',
        'rejected_at'      => 'datetime',
        'last_revised_at'  => 'datetime',
        'deleted_at'       => 'datetime',
    ];

    // ── Status: dokumen & approval workflow ────────────────────────────────────

    /** Status order untuk alur approval transaksi */
    const DOC_STATUS_PENDING_APPROVAL    = 'pending_approval';

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
        'pending_approval'    => ['label' => 'Pending Approval',           'color' => 'warning'],
        'draft'               => ['label' => 'Dokumen Belum Lengkap',      'color' => 'secondary'],
        'waiting_verification'=> ['label' => 'Menunggu Verifikasi',        'color' => 'warning'],
        'revision'            => ['label' => 'Perlu Revisi Dokumen',       'color' => 'danger'],
        'verified'            => ['label' => 'Dokumen Terverifikasi',      'color' => 'success'],
        'completed'           => ['label' => 'Dokumen Terverifikasi',      'color' => 'success'],
        // Legacy
        'pending'             => ['label' => 'Menunggu',                   'color' => 'warning'],
        'approved'            => ['label' => 'Disetujui',                  'color' => 'success'],
        'processing'          => ['label' => 'Diproses',                   'color' => 'info'],
        'cancelled'           => ['label' => 'Dibatalkan',                 'color' => 'secondary'],
        'rejected'            => ['label' => 'Ditolak',                    'color' => 'danger'],
    ];

    // ── Payment statuses ──────────────────────────────────────────────────────

    const PAYMENT_STATUSES = [
        'unpaid'   => ['label' => 'Belum Bayar',               'color' => 'secondary'],
        'verified' => ['label' => 'Pembayaran Terverifikasi',  'color' => 'success'],
        'rejected' => ['label' => 'Pembayaran Ditolak',        'color' => 'danger'],
    ];

    /** Extended lookup for label resolution */
    const ALL_PAYMENT_STATUSES = [
        'unpaid'               => ['label' => 'Belum Bayar',               'color' => 'secondary'],
        'pending_verification' => ['label' => 'Menunggu Verifikasi',       'color' => 'warning'],
        'verified'             => ['label' => 'Pembayaran Terverifikasi',  'color' => 'success'],
        'rejected'             => ['label' => 'Pembayaran Ditolak',        'color' => 'danger'],
    ];

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::ALL_PAYMENT_STATUSES[$this->payment_status]['label'] ?? ucfirst($this->payment_status);
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return self::ALL_PAYMENT_STATUSES[$this->payment_status]['color'] ?? 'secondary';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_MAP[$this->status]['label'] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_MAP[$this->status]['color'] ?? 'secondary';
    }

    // ── QR Code ───────────────────────────────────────────────────────────────

    public function generateQrToken(): string
    {
        if (empty($this->qr_token)) {
            do {
                $token = \Illuminate\Support\Str::random(48);
            } while (self::where('qr_token', $token)->exists());

            $this->forceFill(['qr_token' => $token])->save();
        }

        return $this->qr_token;
    }

    public function getQrUrlAttribute(): ?string
    {
        if (empty($this->qr_token)) {
            return null;
        }

        $baseUrl = env('QR_BASE_URL', 'https://lawgika.co.id');
        return rtrim($baseUrl, '/') . '/qr/' . $this->qr_token;
    }

    /**
     * URL lokal untuk preview langsung di browser admin localhost
     */
    public function getLocalQrUrlAttribute(): ?string
    {
        return $this->qr_token ? url('/qr/' . $this->qr_token) : null;
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class)->orderByDesc('created_at')->orderByDesc('id');
    }

    public function auditLogs()
    {
        return $this->hasMany(OrderAuditLog::class)->orderByDesc('created_at')->orderByDesc('id');
    }

    public function changeRequests()
    {
        return $this->hasMany(OrderChangeRequest::class)->orderByDesc('created_at')->orderByDesc('id');
    }

    public function pendingChangeRequests()
    {
        return $this->hasMany(OrderChangeRequest::class)->where('status', 'pending')->orderByDesc('created_at');
    }

    // ── Business Helpers ──────────────────────────────────────────────────────

    public static function generateInvoiceNumber(?Carbon $transactionDate = null): string
    {
        $datePart = ($transactionDate ?? now())->format('Ymd');
        do {
            $candidate = 'INV-' . $datePart . '-' . strtoupper(\Illuminate\Support\Str::random(5));
        } while (self::withTrashed()->where('invoice_number', $candidate)->exists());

        return $candidate;
    }

    public function getEffectiveTransactionDateAttribute(): Carbon
    {
        if ($this->transaction_date) {
            return $this->transaction_date instanceof Carbon
                ? $this->transaction_date->copy()
                : Carbon::parse($this->transaction_date);
        }

        return ($this->created_at ?? now())->copy();
    }

    public function getBenefitStartDateAttribute(): Carbon
    {
        return $this->effective_transaction_date->copy()->startOfDay();
    }

    public function getBenefitEndDateAttribute(): Carbon
    {
        return $this->benefit_start_date->copy()->addYear();
    }

    public function isValidated(): bool
    {
        return in_array($this->status, [
            self::DOC_STATUS_APPROVED,
            self::DOC_STATUS_PROCESSING,
            self::DOC_STATUS_COMPLETED,
            self::DOC_STATUS_VERIFIED,
        ], true);
    }

    public function isPendingApproval(): bool
    {
        return in_array($this->status, [
            self::DOC_STATUS_PENDING_APPROVAL,
            self::DOC_STATUS_PENDING,
        ], true);
    }

    public function isRejected(): bool
    {
        return $this->status === self::DOC_STATUS_REJECTED;
    }
}
