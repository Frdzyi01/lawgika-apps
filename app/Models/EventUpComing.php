<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventUpComing extends Model
{
    /** @use HasFactory<\Database\Factories\EventUpComingFactory> */
    use HasFactory;

    protected $fillable = [
        'nama_event',
        'deskripsi',
        'banner',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_event',
        'kapasitas',
        'narasumber',
        'harga',
        'tipe_event',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'status'          => 'boolean',
        'narasumber'      => 'array',
        'kapasitas'       => 'integer',
        'harga'           => 'integer',
    ];

    /**
     * Sertakan accessor ini secara otomatis dalam output JSON/array
     */
    protected $appends = [
        'status_aktif',
        'label_status',
    ];

    /**
     * Status otomatis: aktif jika tanggal_selesai >= hari ini DAN status = true
     */
    public function getStatusAktifAttribute(): bool
    {
        return $this->status && $this->tanggal_selesai->gte(Carbon::today());
    }

    /**
     * Label status: "Aktif" atau "Selesai"
     */
    public function getLabelStatusAttribute(): string
    {
        return $this->status_aktif ? 'Aktif' : 'Selesai';
    }
}
