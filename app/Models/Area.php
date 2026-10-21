<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use BelongsToTenant;

    protected $guarded = [];

    protected $casts = [
        'is_delivery' => 'boolean',
        'can_generate_quotation' => 'boolean',
        'can_view_quotation_amount' => 'boolean',
        'can_collect_payment' => 'boolean',
        'order' => 'integer',
    ];

    public const STATUS_OPTIONS = [
        'recibido' => 'Recibido / Ingreso',
        'presupuesto' => 'Presupuesto / Diagnóstico',
        'en_espera' => 'En Espera (Repuestos / Autorización)',
        'trabajando' => 'En Proceso / Trabajando',
        'revision' => 'En Revisión / Control de Calidad',
        'terminado' => 'Terminado / Listo para Entrega',
    ];

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'current_area_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Sugiere un estado de orden según el nombre del área.
     */
    public static function suggestStatusFromName(string $name): string
    {
        $normalized = mb_strtolower(trim($name));

        if (str_contains($normalized, 'recep') || str_contains($normalized, 'ingreso')) {
            return 'recibido';
        }
        if (str_contains($normalized, 'diag') || str_contains($normalized, 'presup') || str_contains($normalized, 'cotiz')) {
            return 'presupuesto';
        }
        if (str_contains($normalized, 'espera') || str_contains($normalized, 'repuest')) {
            return 'en_espera';
        }
        if (str_contains($normalized, 'calidad') || str_contains($normalized, 'control') || str_contains($normalized, 'revis') || str_contains($normalized, 'lavad') || str_contains($normalized, 'detailing')) {
            return 'revision';
        }
        if (str_contains($normalized, 'entreg') || str_contains($normalized, 'listo') || str_contains($normalized, 'terminad')) {
            return 'terminado';
        }

        return 'trabajando';
    }
}
