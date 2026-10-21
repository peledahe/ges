<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ChecklistTemplate extends Model
{
    use BelongsToTenant;

    protected $guarded = [];

    /**
     * Obtiene los items del checklist adaptados al tipo de vehículo.
     */
    public static function getItemsForVehicleType(?string $type = null, ?int $tenantId = null): array
    {
        $normalizedType = strtolower($type ?? '');
        
        $category = match ($normalizedType) {
            'pasola' => 'pasola',
            'moto' => 'moto',
            'pickup', 'pickup_double' => 'pickup',
            'camion', 'cabezal', 'bus', 'bus_escolar', 'van' => 'pesado',
            default => 'sedan',
        };

        // Si existe una plantilla guardada en BD para este taller que coincida
        if ($tenantId) {
            $template = static::withoutGlobalScopes()
                ->where('tenant_id', $tenantId)
                ->where(function ($q) use ($category, $normalizedType) {
                    $q->where('name', 'like', "%{$category}%")
                      ->orWhere('name', 'like', "%{$normalizedType}%");
                })
                ->first();

            if ($template && !empty($template->items)) {
                $decoded = is_string($template->items) ? json_decode($template->items, true) : $template->items;
                if (is_array($decoded) && !empty($decoded)) {
                    return $decoded;
                }
            }
        }

        // Si no hay plantilla personalizada, devolver catálogo predeterminado
        return static::getDefaultTemplateItems($category);
    }

    /**
     * Plantillas predeterminadas según la categoría del vehículo.
     */
    public static function getDefaultTemplateItems(string $category): array
    {
        return match ($category) {
            'pasola' => [
                [
                    'section' => 'Parte Delantera / Mandos',
                    'items' => [
                        'Faro Delantero / Silvín',
                        'Pidevías Delanteros',
                        'Espejos Retrovisores',
                        'Manecillas de Freno (Del./Tras.)',
                        'Tablero / Odómetro',
                        'Carenaje Frontal',
                        'Piso / Apoyapiés',
                        'Llanta Delantera',
                    ],
                ],
                [
                    'section' => 'Parte Trasera / Motor',
                    'items' => [
                        'Luz Trasera / Stop',
                        'Pidevías Traseros',
                        'Luz de Placa',
                        'Sillín / Asiento',
                        'Baúl debajo del Sillín',
                        'Tapa de Tanque / Batería',
                        'Escape / Silenciador',
                        'Llanta Trasera',
                        'Parador Central y Lateral',
                    ],
                ],
            ],

            'moto' => [
                [
                    'section' => 'Parte Delantera / Mandos',
                    'items' => [
                        'Faro Delantero / Silvín',
                        'Pidevías Delanteros',
                        'Espejos Retrovisores',
                        'Manecilla de Freno Delantero',
                        'Manecilla de Embrague',
                        'Manubrio / Timón',
                        'Tablero / Tacómetro',
                        'Llanta Delantera',
                    ],
                ],
                [
                    'section' => 'Parte Posterior / Transmisión',
                    'items' => [
                        'Luz de Freno / Stop',
                        'Pidevías Traseros',
                        'Luz de Placa',
                        'Asiento / Sillín',
                        'Escape / Silenciador',
                        'Cadena de Transmisión',
                        'Pedal de Freno y Cambios',
                        'Llanta Trasera',
                        'Pata de Apoyo / Parador',
                    ],
                ],
            ],

            'pickup' => [
                [
                    'section' => 'Parte Frontal / Cabina',
                    'items' => [
                        'Luces Delanteras',
                        'Pidevías Delanteros',
                        'Emblema',
                        'Bumper Delantero',
                        'Plumillas / Limpiaparabrisas',
                        'Antena',
                        'Retrovisores Exteriores',
                    ],
                ],
                [
                    'section' => 'Palangana / Parte Trasera',
                    'items' => [
                        'Compuerta de Palangana',
                        'Protector de Palangana (Duraliner)',
                        'Luces Traseras / Stop',
                        'Luz de Placa',
                        'Llanta de Repuesto',
                        'Tricket / Gato',
                        'Llave de Chucho',
                    ],
                ],
            ],

            'pesado' => [
                [
                    'section' => 'Parte Frontal / Cabina',
                    'items' => [
                        'Luces Principales (Altas/Bajas)',
                        'Luces de Galibo / Altura',
                        'Espejos Panorámicos y Ángulo Muerto',
                        'Parabrisas y Plumillas Pesadas',
                        'Bumper / Barra Delantera',
                        'Escalones de Acceso',
                    ],
                ],
                [
                    'section' => 'Equipo / Parte Posterior',
                    'items' => [
                        'Luces Posteriores / Freno',
                        'Alarma de Retroceso',
                        'Llantas de Repuesto',
                        'Gato Hidráulico (Botella)',
                        'Llave de Artillería / Chuchos',
                        'Extintor de Emergencia',
                        'Triángulos / Cuñas de Bloqueo',
                    ],
                ],
            ],

            // Automóviles estándar (Sedan, Hatchback, SUV, etc.)
            default => [
                [
                    'section' => 'Parte Frontal',
                    'items' => [
                        'Antena',
                        'Emblema',
                        'Spoiler Delantero',
                        'Luces Delanteras',
                        'Plumillas',
                    ],
                ],
                [
                    'section' => 'Parte Trasera / Baúl',
                    'items' => [
                        'Emblema',
                        'Luz de Placa',
                        'Tapicería de Baúl',
                        'Herramientas',
                        'Llave de Chucho',
                        'Tricket',
                        'Llanta de Repuesto',
                    ],
                ],
            ],
        };
    }
}
