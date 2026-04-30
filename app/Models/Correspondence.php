<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correspondence extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // ── Relasi ────────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Correspondence::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Correspondence::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'secondary',
            'replied'  => 'primary',
            'done'     => 'success',
            default    => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'Menunggu',
            'replied'  => 'Dibalas',
            'done'     => 'Selesai',
            default    => ucfirst($this->status),
        };
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeFromCustomer($query)
    {
        return $query->where('sender_role', 'customer');
    }
}
