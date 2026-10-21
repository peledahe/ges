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
        'paid_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'current_area_id');
    }

    public function checklists()
    {
        return $this->hasMany(WorkOrderChecklist::class);
    }

    public function photos()
    {
        return $this->hasMany(WorkOrderPhoto::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'pagado';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'entregado';
    }

    public function pendingPaymentAmount(): float
    {
        $total = (float) ($this->total_amount ?? 0);
        $paid = (float) ($this->paid_amount ?? 0);
        return max(0, $total - $paid);
    }
}

