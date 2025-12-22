<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Area extends Model
{
    use BelongsToTenant;
    
    protected $guarded = [];

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'current_area_id');
    }
}
