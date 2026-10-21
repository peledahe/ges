<?php

namespace App\Livewire;

use App\Models\Tenant;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class PublicTracking extends Component
{
    #[Url]
    public string $plate = '';

    public ?WorkOrder $workOrder = null;
    public ?Vehicle $vehicle = null;
    public ?Tenant $tenant = null;
    public ?string $tenantSlug = null;
    public bool $searched = false;

    public function mount(?Tenant $tenant = null, ?string $tenant_slug = null): void
    {
        // Si se inyectó modelo Tenant por route model binding ({tenant:slug})
        if ($tenant && $tenant->exists) {
            $this->tenant = $tenant;
            $this->tenantSlug = $tenant->slug;
        } elseif ($tenant_slug) {
            $this->tenant = Tenant::where('slug', $tenant_slug)->first();
            $this->tenantSlug = $this->tenant?->slug;
        }

        if (!empty($this->plate)) {
            $this->search();
        }
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.public-tracking');
    }

    public function search(): void
    {
        $this->validate([
            'plate' => 'required|min:2|max:20',
        ], [
            'plate.required' => 'Por favor, ingresa el número de placa.',
            'plate.min' => 'El número de placa debe tener al menos 2 caracteres.',
        ]);

        $this->searched = true;

        $rawPlate = trim($this->plate);
        $cleanPlate = str_replace([' ', '-'], '', mb_strtoupper($rawPlate));

        // 1. Si la URL tiene un tenant específico, restringir la búsqueda a ese taller
        if ($this->tenant) {
            $vehicle = Vehicle::withoutGlobalScopes()
                ->where('tenant_id', $this->tenant->id)
                ->where(function ($q) use ($rawPlate, $cleanPlate) {
                    $q->where('plate', $rawPlate)
                      ->orWhere('plate', $cleanPlate)
                      ->orWhereRaw("REPLACE(REPLACE(UPPER(plate), ' ', ''), '-', '') = ?", [$cleanPlate]);
                })
                ->first();

            $this->vehicle = $vehicle;

            if ($vehicle) {
                // Cargar orden activa de ese taller
                $this->workOrder = WorkOrder::withoutGlobalScopes()
                    ->where('tenant_id', $this->tenant->id)
                    ->where('vehicle_id', $vehicle->id)
                    ->where('status', '!=', 'entregado')
                    ->with(['vehicle', 'area', 'checklists', 'photos'])
                    ->latest()
                    ->first();

                if (!$this->workOrder) {
                    $this->workOrder = WorkOrder::withoutGlobalScopes()
                        ->where('tenant_id', $this->tenant->id)
                        ->where('vehicle_id', $vehicle->id)
                        ->with(['vehicle', 'area', 'checklists', 'photos'])
                        ->latest()
                        ->first();
                }
            } else {
                $this->workOrder = null;
            }

            return;
        }

        // 2. Modo global fallback (cuando entran a /rastreo sin slug)
        $vehicle = Vehicle::withoutGlobalScopes()
            ->where(function ($q) use ($rawPlate, $cleanPlate) {
                $q->where('plate', $rawPlate)
                  ->orWhere('plate', $cleanPlate)
                  ->orWhereRaw("REPLACE(REPLACE(UPPER(plate), ' ', ''), '-', '') = ?", [$cleanPlate]);
            })
            ->first();

        $this->vehicle = $vehicle;

        if ($vehicle) {
            // Cargar la orden activa más reciente (o la última entregada si ya finalizó)
            $this->workOrder = WorkOrder::withoutGlobalScopes()
                ->where('vehicle_id', $vehicle->id)
                ->where('status', '!=', 'entregado')
                ->with(['vehicle', 'area', 'checklists', 'photos'])
                ->latest()
                ->first();

            if (!$this->workOrder) {
                $this->workOrder = WorkOrder::withoutGlobalScopes()
                    ->where('vehicle_id', $vehicle->id)
                    ->with(['vehicle', 'area', 'checklists', 'photos'])
                    ->latest()
                    ->first();
            }

            if ($this->workOrder && $this->workOrder->tenant_id) {
                $this->tenant = Tenant::find($this->workOrder->tenant_id);
            } elseif ($vehicle->tenant_id) {
                $this->tenant = Tenant::find($vehicle->tenant_id);
            }
        } else {
            $this->workOrder = null;
            $this->tenant = null;
        }
    }
}
