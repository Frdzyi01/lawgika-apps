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

    /** Payment statuses */
    const PAYMENT_STATUSES = [
        'unpaid'               => ['label' => 'Belum Bayar',          'color' => 'secondary'],
        'pending_verification' => ['label' => 'Menunggu Verifikasi',  'color' => 'warning'],
        'verified'             => ['label' => 'Pembayaran Terverifikasi', 'color' => 'success'],
        'rejected'             => ['label' => 'Pembayaran Ditolak',   'color' => 'danger'],
    ];

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status]['label'] ?? ucfirst($this->payment_status);
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status]['color'] ?? 'secondary';
    }

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
}
