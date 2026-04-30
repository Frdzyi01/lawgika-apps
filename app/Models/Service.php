<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // ── Relasi ────────────────────────────────────────────────────────────────

    public function documentRequirements()
    {
        return $this->hasMany(DocumentRequirement::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
