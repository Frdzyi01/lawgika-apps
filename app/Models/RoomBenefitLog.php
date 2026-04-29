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
    ];

    protected $casts = [
        'action_at' => 'datetime',
    ];

    public function benefit(): BelongsTo
    {
        return $this->belongsTo(RoomBenefit::class, 'benefit_id');
    }
}
