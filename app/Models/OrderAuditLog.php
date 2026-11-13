<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAuditLog extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'user_role',
        'action',
        'reason',
        'old_values',
        'new_values',
        'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create' => 'Buat Transaksi',
            'update' => 'Edit Transaksi',
            'delete' => 'Hapus Transaksi',
            'approve' => 'Approve Transaksi',
            'reject' => 'Reject Transaksi',
            'resubmit' => 'Revisi & Submit Ulang',
            'payment_update' => 'Update Pembayaran',
            'change_request_create' => 'Ajukan Edit/Hapus',
            'change_request_approve' => 'Setujui Pengajuan Edit/Hapus',
            'change_request_reject' => 'Tolak Pengajuan Edit/Hapus',
            'document_upload' => 'Upload Dokumen',
            'document_update' => 'Edit / Ganti Dokumen',
            default => ucfirst(str_replace('_', ' ', (string) $this->action)),
        };
    }
}

