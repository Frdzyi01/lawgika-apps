<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderChangeRequest extends Model
{
    protected $fillable = [
        'order_id',
        'requested_by',
        'request_type',
        'status',
        'reason',
        'proposed_data',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    protected $casts = [
        'proposed_data' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}

