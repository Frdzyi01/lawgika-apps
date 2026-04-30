<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequirement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // ── Relasi ────────────────────────────────────────────────────────────────

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // ── Scope ─────────────────────────────────────────────────────────────────

    /** Ambil semua requirement untuk satu service */
    public function scopeForService($query, int $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }
}
