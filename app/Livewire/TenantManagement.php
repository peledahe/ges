<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Scopes\TenantScope;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class TenantManagement extends Component
{
    use WithPagination;

    // Filtros y búsqueda
    public string $search = '';
    public string $statusFilter = 'all'; // all, active, inactive

    // Modal de Creación / Edición de Tenant
    public bool $managingTenant = false;
    public ?int $tenantId = null;
    public string $name = '';
    public string $slug = '';
    public string $admin_name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public int $users_limit = 5;
    public float $exchange_rate = 7.80;
    public string $default_currency = 'GTQ';
    public bool $is_active = true;
    public array $allowed_vehicle_types = [];

    // Modal de Confirmación de Borrado de Tenant
    public bool $confirmingTenantDeletion = false;
    public ?int $tenantToDeleteId = null;
    public string $tenantToDeleteName = '';

    // Modal de Gestión de Áreas del Tenant
    public bool $managingAreas = false;
    public ?int $areasTenantId = null;
    public ?Tenant $currentAreasTenant = null;
    public string $newAreaName = '';
    public string $newAreaDescription = '';
    public bool $newAreaCanGenerateQuotation = false;
    public bool $newAreaCanViewQuotationAmount = true;
    public bool $newAreaCanCollectPayment = false;
    public bool $newAreaIsDelivery = false;

    // Modal de Confirmación de Borrado de Área
    public bool $confirmingAreaDeletion = false;
    public ?int $areaToDeleteId = null;
    public string $areaToDeleteName = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatedName($value): void
    {
        if (!$this->tenantId && empty($this->slug)) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:2|max:255|unique:tenants,slug,' . $this->tenantId,
            'admin_name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'users_limit' => 'required|integer|min:1|max:500',
            'exchange_rate' => 'required|numeric|min:0.01|max:999.99',
            'default_currency' => 'required|in:GTQ,USD',
            'is_active' => 'boolean',
            'allowed_vehicle_types' => 'array',
        ];
    }

    public function createTenant(): void
    {
        $this->resetValidation();
        $this->reset([
            'tenantId',
            'name',
            'slug',
            'admin_name',
            'email',
            'phone',
            'address',
            'users_limit',
            'exchange_rate',
            'default_currency',
            'is_active',
            'allowed_vehicle_types',
        ]);

        $this->users_limit = 5;
        $this->exchange_rate = 7.80;
        $this->default_currency = 'GTQ';
        $this->is_active = true;
        // Por defecto todos los tipos de vehículos seleccionados
        $this->allowed_vehicle_types = array_keys(Tenant::ALL_VEHICLE_TYPES);
        $this->managingTenant = true;
    }

    public function editTenant(int $id): void
    {
        $this->resetValidation();
        $tenant = Tenant::findOrFail($id);

        $this->tenantId = $tenant->id;
        $this->name = $tenant->name;
        $this->slug = $tenant->slug;
        $this->admin_name = $tenant->admin_name;
        $this->email = $tenant->email;
        $this->phone = $tenant->phone ?? '';
        $this->address = $tenant->address ?? '';
        $this->users_limit = $tenant->users_limit ?? 5;
        $this->exchange_rate = (float) ($tenant->exchange_rate ?? 7.80);
        $this->default_currency = $tenant->default_currency ?? 'GTQ';
        $this->is_active = (bool) $tenant->is_active;
        $this->allowed_vehicle_types = $tenant->allowed_vehicle_types ?? array_keys(Tenant::ALL_VEHICLE_TYPES);

        $this->managingTenant = true;
    }

    public function saveTenant(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'admin_name' => $this->admin_name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'users_limit' => $this->users_limit,
            'exchange_rate' => (float) $this->exchange_rate,
            'default_currency' => $this->default_currency,
            'is_active' => $this->is_active,
            'allowed_vehicle_types' => !empty($this->allowed_vehicle_types) ? array_values($this->allowed_vehicle_types) : null,
        ];

        if ($this->tenantId) {
            $tenant = Tenant::findOrFail($this->tenantId);
            $tenant->update($data);
            $message = "El taller '{$tenant->name}' ha sido actualizado exitosamente.";
        } else {
            $tenant = Tenant::create($data);

            // Crear automáticamente área de Recepción si es nuevo
            Area::create([
                'tenant_id' => $tenant->id,
                'name' => 'Recepción',
                'description' => 'Área inicial de ingreso y evaluación de vehículos',
            ]);

            $message = "El taller '{$tenant->name}' ha sido registrado exitosamente.";
        }

        $this->managingTenant = false;
        $this->dispatch('notify', message: $message, type: 'success');
    }

    public function toggleTenantStatus(int $id): void
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->is_active = !$tenant->is_active;
        $tenant->save();

        $statusText = $tenant->is_active ? 'activado' : 'desactivado';
        $this->dispatch('notify', message: "El taller '{$tenant->name}' ha sido {$statusText}.", type: 'info');
    }

    public function confirmTenantDeletion(int $id): void
    {
        $tenant = Tenant::findOrFail($id);
        $this->tenantToDeleteId = $tenant->id;
        $this->tenantToDeleteName = $tenant->name;
        $this->confirmingTenantDeletion = true;
    }

    public function deleteTenant(): void
    {
        if ($this->tenantToDeleteId) {
            $tenant = Tenant::find($this->tenantToDeleteId);
            if ($tenant) {
                $name = $tenant->name;
                $tenant->delete();
                $this->dispatch('notify', message: "El taller '{$name}' ha sido eliminado correctamente.", type: 'warning');
            }
        }

        $this->confirmingTenantDeletion = false;
        $this->tenantToDeleteId = null;
        $this->tenantToDeleteName = '';
    }

    // Helper Presets de Tipos de Vehículos
    public function selectAllVehicleTypes(): void
    {
        $this->allowed_vehicle_types = array_keys(Tenant::ALL_VEHICLE_TYPES);
    }

    public function selectMotorcyclesOnly(): void
    {
        $this->allowed_vehicle_types = ['pasola', 'moto'];
    }

    public function selectLightVehiclesOnly(): void
    {
        $this->allowed_vehicle_types = ['pasola', 'moto', 'hashback', 'sedan', 'suv', 'pickup', 'pickup_double', 'van'];
    }

    public function selectHeavyVehiclesOnly(): void
    {
        $this->allowed_vehicle_types = ['van', 'camion', 'cabezal', 'bus_escolar', 'bus'];
    }

    public function clearVehicleTypes(): void
    {
        $this->allowed_vehicle_types = [];
    }

    // Gestión de Áreas del Taller
    public function manageAreas(int $tenantId): void
    {
        $this->areasTenantId = $tenantId;
        $this->currentAreasTenant = Tenant::findOrFail($tenantId);
        $this->reset(['newAreaName', 'newAreaDescription', 'newAreaCanGenerateQuotation', 'newAreaCanViewQuotationAmount', 'newAreaCanCollectPayment', 'newAreaIsDelivery']);
        $this->newAreaCanViewQuotationAmount = true;
        $this->resetValidation();
        $this->managingAreas = true;
    }

    public function updatedNewAreaName($value): void
    {
        $normalized = mb_strtolower(trim($value));
        if (str_contains($normalized, 'presup') || str_contains($normalized, 'diag') || str_contains($normalized, 'cotiz')) {
            $this->newAreaCanGenerateQuotation = true;
        }
        if (str_contains($normalized, 'entreg') || str_contains($normalized, 'caja') || str_contains($normalized, 'recep')) {
            $this->newAreaCanCollectPayment = true;
        }
        if (str_contains($normalized, 'entreg')) {
            $this->newAreaIsDelivery = true;
        }
    }

    public function addArea(): void
    {
        $this->validate([
            'newAreaName' => 'required|string|min:2|max:100',
            'newAreaDescription' => 'nullable|string|max:255',
        ]);

        if ($this->areasTenantId) {
            $isDelivery = $this->newAreaIsDelivery || str_contains(mb_strtolower($this->newAreaName), 'entreg');
            $status = Area::suggestStatusFromName($this->newAreaName);

            Area::create([
                'tenant_id' => $this->areasTenantId,
                'name' => trim($this->newAreaName),
                'description' => trim($this->newAreaDescription) ?: null,
                'default_status' => $status,
                'is_delivery' => $isDelivery,
                'can_generate_quotation' => $this->newAreaCanGenerateQuotation,
                'can_view_quotation_amount' => $this->newAreaCanViewQuotationAmount,
                'can_collect_payment' => $this->newAreaCanCollectPayment,
            ]);

            $this->reset(['newAreaName', 'newAreaDescription', 'newAreaCanGenerateQuotation', 'newAreaCanViewQuotationAmount', 'newAreaCanCollectPayment', 'newAreaIsDelivery']);
            $this->newAreaCanViewQuotationAmount = true;
            $this->currentAreasTenant = Tenant::findOrFail($this->areasTenantId);
            $this->dispatch('notify', message: 'Área agregada correctamente al taller.', type: 'success');
        }
    }

    public function toggleAreaQuotation(int $areaId): void
    {
        $area = Area::withoutGlobalScope(TenantScope::class)->findOrFail($areaId);
        $area->can_generate_quotation = !$area->can_generate_quotation;
        $area->save();

        if ($this->areasTenantId) {
            $this->currentAreasTenant = Tenant::findOrFail($this->areasTenantId);
        }

        $stateText = $area->can_generate_quotation ? 'habilitada' : 'deshabilitada';
        $this->dispatch('notify', message: "Generación de cotización {$stateText} para '{$area->name}'.", type: 'info');
    }

    public function toggleAreaViewAmount(int $areaId): void
    {
        $area = Area::withoutGlobalScope(TenantScope::class)->findOrFail($areaId);
        $area->can_view_quotation_amount = !$area->can_view_quotation_amount;
        $area->save();

        if ($this->areasTenantId) {
            $this->currentAreasTenant = Tenant::findOrFail($this->areasTenantId);
        }

        $stateText = $area->can_view_quotation_amount ? 'visible' : 'oculto';
        $this->dispatch('notify', message: "Visualización de montos {$stateText} para '{$area->name}'.", type: 'info');
    }

    public function toggleAreaCollectPayment(int $areaId): void
    {
        $area = Area::withoutGlobalScope(TenantScope::class)->findOrFail($areaId);
        $area->can_collect_payment = !$area->can_collect_payment;
        $area->save();

        if ($this->areasTenantId) {
            $this->currentAreasTenant = Tenant::findOrFail($this->areasTenantId);
        }

        $stateText = $area->can_collect_payment ? 'habilitado' : 'deshabilitado';
        $this->dispatch('notify', message: "Cobro de pagos {$stateText} para '{$area->name}'.", type: 'info');
    }

    public function loadRecommendedAreas(): void
    {
        if (!$this->areasTenantId) {
            return;
        }

        $recommended = [
            ['name' => 'Recepción', 'description' => 'Ingreso de vehículos, inventario y hoja de recepción.', 'status' => 'recibido', 'delivery' => false, 'quote' => false, 'view_amount' => true, 'collect' => true],
            ['name' => 'Diagnóstico y Presupuestos', 'description' => 'Evaluación técnica, escaneo y generación de cotización.', 'status' => 'presupuesto', 'delivery' => false, 'quote' => true, 'view_amount' => true, 'collect' => false],
            ['name' => 'En Espera de Repuestos', 'description' => 'Vehículos en espera de refacciones o autorización del cliente/aseguradora.', 'status' => 'en_espera', 'delivery' => false, 'quote' => false, 'view_amount' => true, 'collect' => false],
            ['name' => 'Mecánica Rápida', 'description' => 'Servicio menor, cambio de aceite, frenos y fluidos.', 'status' => 'trabajando', 'delivery' => false, 'quote' => false, 'view_amount' => false, 'collect' => false],
            ['name' => 'Mecánica Mayor', 'description' => 'Reparación de motor, transmisión, suspensión y dirección.', 'status' => 'trabajando', 'delivery' => false, 'quote' => false, 'view_amount' => false, 'collect' => false],
            ['name' => 'Enderezado y Pintura', 'description' => 'Reparación de carrocería, lijado, aplicación de pintura y pulido.', 'status' => 'trabajando', 'delivery' => false, 'quote' => false, 'view_amount' => false, 'collect' => false],
            ['name' => 'Control de Calidad y Lavado', 'description' => 'Inspección final de pruebas y limpieza previa a entrega.', 'status' => 'revision', 'delivery' => false, 'quote' => false, 'view_amount' => false, 'collect' => false],
            ['name' => 'Entrega', 'description' => 'Área de entrega del vehículo al cliente y liquidación.', 'status' => 'terminado', 'delivery' => true, 'quote' => false, 'view_amount' => true, 'collect' => true],
        ];

        $existingNames = Area::withoutGlobalScope(TenantScope::class)
            ->where('tenant_id', $this->areasTenantId)
            ->pluck('name')
            ->map(fn($n) => mb_strtolower(trim($n)))
            ->toArray();

        $addedCount = 0;
        foreach ($recommended as $rec) {
            if (!in_array(mb_strtolower($rec['name']), $existingNames)) {
                Area::create([
                    'tenant_id' => $this->areasTenantId,
                    'name' => $rec['name'],
                    'description' => $rec['description'],
                    'default_status' => $rec['status'],
                    'is_delivery' => $rec['delivery'],
                    'can_generate_quotation' => $rec['quote'],
                    'can_view_quotation_amount' => $rec['view_amount'],
                    'can_collect_payment' => $rec['collect'],
                ]);
                $addedCount++;
            }
        }

        $this->currentAreasTenant = Tenant::findOrFail($this->areasTenantId);
        $this->dispatch('notify', message: "Se agregaron {$addedCount} áreas recomendadas con permisos configurados.", type: 'success');
    }

    public function confirmAreaDeletion(int $areaId): void
    {
        $area = Area::withoutGlobalScope(TenantScope::class)->findOrFail($areaId);
        $this->areaToDeleteId = $area->id;
        $this->areaToDeleteName = $area->name;
        $this->confirmingAreaDeletion = true;
    }

    public function deleteArea(): void
    {
        if ($this->areaToDeleteId) {
            $area = Area::withoutGlobalScope(TenantScope::class)->find($this->areaToDeleteId);
            if ($area) {
                $name = $area->name;
                $area->delete();
                if ($this->areasTenantId) {
                    $this->currentAreasTenant = Tenant::findOrFail($this->areasTenantId);
                }
                $this->dispatch('notify', message: "El área '{$name}' ha sido eliminada.", type: 'warning');
            }
        }

        $this->confirmingAreaDeletion = false;
        $this->areaToDeleteId = null;
        $this->areaToDeleteName = '';
    }

    #[Layout('layouts.app')]
    public function render()
    {
        // Estadísticas Globales del Dashboard Root
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('is_active', true)->count();
        $inactiveTenants = $totalTenants - $activeTenants;
        $totalOrders = WorkOrder::withoutGlobalScope(TenantScope::class)->count();
        $totalVehicles = Vehicle::withoutGlobalScope(TenantScope::class)->count();
        $totalUsers = User::count();

        // Consulta de Tenants con búsqueda y filtros
        $tenantsQuery = Tenant::query()
            ->withCount([
                'areas' => fn($q) => $q->withoutGlobalScope(TenantScope::class),
                'users',
                'vehicles' => fn($q) => $q->withoutGlobalScope(TenantScope::class),
                'workOrders' => fn($q) => $q->withoutGlobalScope(TenantScope::class),
            ]);

        if (!empty($this->search)) {
            $term = '%' . trim($this->search) . '%';
            $tenantsQuery->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('slug', 'like', $term)
                    ->orWhere('admin_name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term);
            });
        }

        if ($this->statusFilter === 'active') {
            $tenantsQuery->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $tenantsQuery->where('is_active', false);
        }

        $tenants = $tenantsQuery->latest()->paginate(10);

        // Áreas del taller actual si el modal de áreas está abierto
        $currentTenantAreas = [];
        if ($this->managingAreas && $this->areasTenantId) {
            $currentTenantAreas = Area::withoutGlobalScope(TenantScope::class)
                ->where('tenant_id', $this->areasTenantId)
                ->withCount(['workOrders' => fn($q) => $q->withoutGlobalScope(TenantScope::class)])
                ->orderBy('id', 'asc')
                ->get();
        }

        return view('livewire.tenant-management', [
            'metrics' => [
                'total_tenants' => $totalTenants,
                'active_tenants' => $activeTenants,
                'inactive_tenants' => $inactiveTenants,
                'total_orders' => $totalOrders,
                'total_vehicles' => $totalVehicles,
                'total_users' => $totalUsers,
            ],
            'tenants' => $tenants,
            'currentTenantAreas' => $currentTenantAreas,
            'allVehicleTypes' => Tenant::ALL_VEHICLE_TYPES,
        ]);
    }
}
