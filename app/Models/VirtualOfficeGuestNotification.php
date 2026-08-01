<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualOfficeGuestNotification extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relationship: Order / Virtual Office Order
     */
    public function virtualOffice()
    {
        return $this->belongsTo(Order::class, 'virtual_office_id');
    }

    /**
     * Relationship: User / Client
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Relationship: User / Creator (Admin)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
