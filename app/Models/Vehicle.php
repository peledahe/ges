<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Illuminate\Notifications\Notifiable;

class Vehicle extends Model
{
    use BelongsToTenant, Notifiable;

    protected $guarded = [];
    
    public function routeNotificationForMail($notification)
    {
        // Return owner email
        return $this->owner_email;
    }
}
