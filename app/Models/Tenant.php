<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $guarded = [];

    protected $casts = [
        'allowed_vehicle_types' => 'array',
        'is_active' => 'boolean',
        'users_limit' => 'integer',
        'trial_ends_at' => 'datetime',
    ];

    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function trialDaysRemaining(): int
    {
        if (!$this->onTrial()) {
            return 0;
        }
        return (int) ceil(now()->diffInDays($this->trial_ends_at, false));
    }

    public const ALL_VEHICLE_TYPES = [
        'pasola' => 'Pasola / Scooter',
        'moto' => 'Motocicleta',
        'hashback' => 'Hatchback',
        'sedan' => 'Sedán',
        'suv' => 'Camioneta (SUV)',
        'pickup' => 'Pickup',
        'pickup_double' => 'Pickup Doble Cabina',
        'van' => 'Van / Furgoneta',
        'camion' => 'Camión de Carga',
        'cabezal' => 'Cabezal de Tráiler',
        'bus_escolar' => 'Bus Escolar',
        'bus' => 'Autobús',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Retorna los tipos de vehículos que este taller puede atender.
     * Si no se han configurado específicamente, retorna todos los tipos por defecto.
     */
    public function getVehicleTypes(): array
    {
        if (empty($this->allowed_vehicle_types)) {
            return static::ALL_VEHICLE_TYPES;
        }

        $filtered = [];
        foreach ($this->allowed_vehicle_types as $typeKey) {
            if (isset(static::ALL_VEHICLE_TYPES[$typeKey])) {
                $filtered[$typeKey] = static::ALL_VEHICLE_TYPES[$typeKey];
            }
        }

        return !empty($filtered) ? $filtered : static::ALL_VEHICLE_TYPES;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function isDemo(): bool
    {
        return $this->slug === 'taller-demo';
    }
}
