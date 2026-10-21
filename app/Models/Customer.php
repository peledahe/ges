<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use BelongsToTenant;

    protected $guarded = [];

    /**
     * Vehículos pertenecientes al cliente.
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Historial de órdenes de trabajo / reparaciones del cliente.
     */
    public function workOrders()
    {
        return $this->hasManyThrough(WorkOrder::class, Vehicle::class);
    }

    /**
     * Scope para búsqueda flexible por teléfono, correo, NIT o nombre.
     */
    public function scopeSearch($query, $term)
    {
        $term = trim($term);
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('nit', 'like', "%{$term}%")
              ->orWhere('name', 'like', "%{$term}%");
        });
    }
}
