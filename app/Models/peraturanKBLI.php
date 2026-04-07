<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peraturanKBLI extends Model
{
    /** @use HasFactory<\Database\Factories\PeraturanKBLIFactory> */
    use HasFactory;

    protected $fillable = [
        'kode_kbli',
        'judul_peraturan',
        'deskripsi',
        'tanggal_berlaku',
        'status',
        'file_dokumen',
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
    ];
}
