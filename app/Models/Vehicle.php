<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Illuminate\Notifications\Notifiable;

class Vehicle extends Model
{
    use BelongsToTenant, Notifiable;

    protected $guarded = [];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function routeNotificationForMail($notification)
    {
        // Return owner email
        return $this->owner_email ?? $this->customer?->email;
    }
}
