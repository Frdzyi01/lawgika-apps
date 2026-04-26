<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SptBadan extends Model
{
    protected $fillable = [
        'user_id',
        'subject_type',       // 'pribadi' | 'badan'
        // Pribadi fields
        'nama_lengkap',
        'nik',
        // Badan fields
        'perusahaan',
        'npwp_perusahaan',
        // Shared fields
        'nama',
        'jenis_usaha',
        'tahun_pajak',
        'laporan_keuangan',
        'status_lapor',
        'status_pesanan',     // default: 'Menunggu Proses'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Readable display name based on subject type
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->subject_type === 'pribadi'
            ? ($this->nama_lengkap ?? $this->nama ?? '-')
            : ($this->perusahaan ?? '-');
    }

    /**
     * Subject type label
     */
    public function getSubjectLabelAttribute(): string
    {
        return $this->subject_type === 'pribadi' ? 'Pribadi' : 'Badan';
    }
}
