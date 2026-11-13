<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderHistory extends Model
{
    protected $fillable = [
        'order_id',
        'action',
        'from_status',
        'to_status',
        'actor_id',
        'actor_role',
        'notes',
        'snapshot',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'submitted' => 'Pesanan Diajukan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'resubmitted' => 'Revisi Diajukan Ulang',
            'edited' => 'Diubah Langsung',
            'deleted' => 'Dihapus',
            'change_requested' => 'Pengajuan Perubahan',
            'change_approved' => 'Pengajuan Perubahan Disetujui',
            'change_rejected' => 'Pengajuan Perubahan Ditolak',
            default => ucfirst(str_replace('_', ' ', (string) $this->action)),
        };
    }
}

