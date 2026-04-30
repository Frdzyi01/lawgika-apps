<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomBenefitLog extends Model
{
    protected $fillable = [
        'benefit_id',
        'room_type',
        'duration_minutes',
        'action',
        'action_at',
        'checkin_at',
        'checkout_at',
    ];

    protected $casts = [
        'action_at'   => 'datetime',
        'checkin_at'  => 'datetime',
        'checkout_at' => 'datetime',
    ];

    public function benefit(): BelongsTo
    {
        return $this->belongsTo(RoomBenefit::class, 'benefit_id');
    }
}
