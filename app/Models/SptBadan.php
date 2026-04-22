<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SptBadan extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'perusahaan',
        'jenis_usaha',
        'tahun_pajak',
        'laporan_keuangan',
        'status_lapor',
        'status_pesanan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
