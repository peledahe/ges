<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class WorkOrder extends Model
{
    use BelongsToTenant;

    protected $guarded = [];

    protected $casts = [
        'received_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'current_area_id');
    }
}
