<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class WorkshopSettings extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Pestaña activa
    public string $activeTab = 'areas'; // 'areas', 'vehicles' o 'general'

    // Tipos de Vehículos que atiende el taller
    public array $allowedVehicleTypes = [];

    // Datos Generales del Taller
    public $name;
    public $slug;
    public $admin_name;
    public $email;
    public $phone;
    public $address;
    public $exchange_rate = 7.80;
    public $default_currency = 'GTQ';
    public $logo;
    public $currentLogo;

    // Gestión de Áreas
    public bool $managingAreaModal = false;
    public bool $confirmingAreaDeletion = false;
    public ?int $editingAreaId = null;
    public string $areaName = '';
    public string $areaDescription = '';
    public string $areaDefaultStatus = 'trabajando';
    public bool $areaCanGenerateQuotation = false;
    public bool $areaCanViewQuotationAmount = true;
    public bool $areaCanCollectPayment = false;
    public bool $areaIsDelivery = false;
    public int $areaOrder = 0;
    public array $areaSelectedUserIds = [];

    // Eliminación de Área
    public ?int $areaToDeleteId = null;
    public string $areaToDeleteName = '';
    public int $areaToDeleteWorkOrdersCount = 0;

    public function mount(): void
    {
        $user = Auth::user();
        if ($user && $user->tenant) {
            $tenant = $user->tenant;
            $this->name = $tenant->name;
            $this->slug = $tenant->slug;
            $this->admin_name = $tenant->admin_name;
            $this->email = $tenant->email;
            $this->phone = $tenant->phone;
            $this->address = $tenant->address;
            $this->exchange_rate = (float) ($tenant->exchange_rate ?? 7.80);
            $this->default_currency = $tenant->default_currency ?? 'GTQ';
            $this->currentLogo = $tenant->logo_path;
            $this->allowedVehicleTypes = $tenant->allowed_vehicle_types ?? array_keys(Tenant::ALL_VEHICLE_TYPES);
        }
    }

    public function setActiveTab(string $tab): void
    {
        if (in_array($tab, ['areas', 'vehicles', 'general'])) {
            $this->activeTab = $tab;
        }
    }

    public function selectAllVehicleTypes(): void
    {
        $this->allowedVehicleTypes = array_keys(Tenant::ALL_VEHICLE_TYPES);
    }

    public function selectOnlyMotos(): void
    {
        $this->allowedVehicleTypes = ['pasola', 'moto'];
    }

    public function selectOnlyLightVehicles(): void
    {
        $this->allowedVehicleTypes = ['pasola', 'moto', 'hashback', 'sedan', 'suv', 'pickup', 'pickup_double', 'van'];
    }

    public function selectOnlyHeavyVehicles(): void
    {
        $this->allowedVehicleTypes = ['camion', 'cabezal', 'bus_escolar', 'bus'];
    }

    public function clearVehicleTypes(): void
    {
        $this->allowedVehicleTypes = [];
    }

    public function saveVehicleTypes(): void
    {
        $user = Auth::user();
        if (!$user || !$user->tenant) {
            return;
        }

        if (empty($this->allowedVehicleTypes)) {
            $this->dispatch('notify', message: 'Debes seleccionar al menos un tipo de vehículo para tu taller.', type: 'error');
            return;
        }

        $user->tenant->update([
            'allowed_vehicle_types' => array_values($this->allowedVehicleTypes),
        ]);

        $this->dispatch('notify', message: 'Tipos de vehículos actualizados exitosamente para el taller.', type: 'success');
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|min:3',
            'admin_name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'exchange_rate' => 'required|numeric|min:0.01|max:999.99',
            'default_currency' => 'required|in:GTQ,USD',
            'logo' => 'nullable|image|max:1024',
        ]);

        $user = Auth::user();
        if ($user && $user->tenant) {
            $tenant = $user->tenant;

            $data = [
                'name' => $this->name,
                'admin_name' => $this->admin_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'exchange_rate' => (float) $this->exchange_rate,
                'default_currency' => $this->default_currency,
            ];

            if ($this->logo) {
                $path = $this->logo->store('logos', 'public');
                $data['logo_path'] = $path;
            }

            $tenant->update($data);

            if ($this->logo) {
                $this->currentLogo = $data['logo_path'];
                $this->logo = null;
            }

            $this->dispatch('notify', [
                'message' => 'Configuración del taller y tasa de cambio actualizadas correctamente.',
                'type' => 'success',
            ]);
        }
    }

    // GESTIÓN DE ÁREAS
    public function openCreateAreaModal(): void
    {
        $tenantId = session('tenant_id') ?: Auth::user()?->tenant_id;
        $maxOrder = (int) Area::where('tenant_id', $tenantId)->max('order');

        $this->reset([
            'editingAreaId',
            'areaName',
            'areaDescription',
            'areaDefaultStatus',
            'areaCanGenerateQuotation',
            'areaCanViewQuotationAmount',
            'areaCanCollectPayment',
            'areaIsDelivery',
            'areaSelectedUserIds',
        ]);

        $this->areaDefaultStatus = 'trabajando';
        $this->areaCanViewQuotationAmount = true;
        $this->areaOrder = $maxOrder + 1;
        $this->resetValidation();
        $this->managingAreaModal = true;
    }

    public function openEditAreaModal(int $areaId): void
    {
        $area = Area::with('users')->findOrFail($areaId);

        $this->editingAreaId = $area->id;
        $this->areaName = $area->name;
        $this->areaDescription = $area->description ?? '';
        $this->areaDefaultStatus = $area->default_status ?? 'trabajando';
        $this->areaCanGenerateQuotation = (bool) $area->can_generate_quotation;
        $this->areaCanViewQuotationAmount = (bool) $area->can_view_quotation_amount;
        $this->areaCanCollectPayment = (bool) $area->can_collect_payment;
        $this->areaIsDelivery = (bool) $area->is_delivery;
        $this->areaOrder = (int) ($area->order ?? 0);
        $this->areaSelectedUserIds = $area->users->pluck('id')->map(fn($id) => (int)$id)->toArray();

        $this->resetValidation();
        $this->managingAreaModal = true;
    }

    public function updatedAreaName(string $value): void
    {
        if (!$this->editingAreaId) {
            $normalized = mb_strtolower(trim($value));

            $this->areaDefaultStatus = Area::suggestStatusFromName($value);

            if (str_contains($normalized, 'presup') || str_contains($normalized, 'diag') || str_contains($normalized, 'cotiz')) {
                $this->areaCanGenerateQuotation = true;
            }
            if (str_contains($normalized, 'entreg')) {
                $this->areaIsDelivery = true;
                $this->areaCanCollectPayment = true;
            }
            if (str_contains($normalized, 'recep')) {
                $this->areaCanCollectPayment = true;
            }
        }
    }

    public function selectAllUsersForArea(): void
    {
        $tenantId = session('tenant_id') ?: Auth::user()?->tenant_id;
        $this->areaSelectedUserIds = User::where('tenant_id', $tenantId)->pluck('id')->map(fn($id) => (int)$id)->toArray();
    }

    public function clearUsersForArea(): void
    {
        $this->areaSelectedUserIds = [];
    }

    public function saveArea(): void
    {
        $this->validate([
            'areaName' => 'required|string|min:2|max:255',
            'areaDefaultStatus' => 'required|string',
            'areaDescription' => 'nullable|string|max:500',
            'areaOrder' => 'nullable|integer|min:0',
            'areaSelectedUserIds' => 'array',
        ]);

        $tenantId = session('tenant_id') ?: Auth::user()?->tenant_id;

        $data = [
            'name' => trim($this->areaName),
            'description' => $this->areaDescription ?: null,
            'default_status' => $this->areaDefaultStatus,
            'is_delivery' => $this->areaIsDelivery,
            'can_generate_quotation' => $this->areaCanGenerateQuotation,
            'can_view_quotation_amount' => $this->areaCanViewQuotationAmount,
            'can_collect_payment' => $this->areaCanCollectPayment,
            'order' => (int) $this->areaOrder,
        ];

        if ($this->editingAreaId) {
            $area = Area::findOrFail($this->editingAreaId);
            $area->update($data);
            $area->users()->sync($this->areaSelectedUserIds);
            $message = "Área '{$area->name}' actualizada correctamente.";
        } else {
            $data['tenant_id'] = $tenantId;
            $area = Area::create($data);
            $area->users()->sync($this->areaSelectedUserIds);
            $message = "Área '{$area->name}' creada correctamente con sus usuarios asignados.";
        }

        $this->managingAreaModal = false;
        $this->dispatch('notify', [
            'message' => $message,
            'type' => 'success',
        ]);
    }

    public function confirmDeleteArea(int $areaId): void
    {
        $area = Area::withCount('workOrders')->findOrFail($areaId);

        // Regla: el área DEBE estar vacía (sin casos / órdenes activas) para poder borrarse
        if ($area->work_orders_count > 0) {
            $this->dispatch('notify', [
                'message' => "⚠️ No se puede eliminar el área '{$area->name}' porque tiene {$area->work_orders_count} orden(es) de trabajo activa(s). Debes reubicarlas a otra área antes de poder eliminarla.",
                'type' => 'warning',
                'duration' => 7000,
            ]);
            return;
        }

        $this->areaToDeleteId = $area->id;
        $this->areaToDeleteName = $area->name;
        $this->areaToDeleteWorkOrdersCount = 0;
        $this->confirmingAreaDeletion = true;
    }

    public function deleteArea(): void
    {
        if ($this->areaToDeleteId) {
            $area = Area::withCount('workOrders')->find($this->areaToDeleteId);

            if ($area) {
                // Doble verificación de seguridad
                if ($area->workOrders()->count() > 0) {
                    $this->dispatch('notify', [
                        'message' => "El área '{$area->name}' contiene órdenes de trabajo activas y no puede ser eliminada.",
                        'type' => 'error',
                    ]);
                    $this->confirmingAreaDeletion = false;
                    return;
                }

                $name = $area->name;
                $area->users()->detach();
                $area->delete();

                $this->dispatch('notify', [
                    'message' => "El área '{$name}' ha sido eliminada exitosamente.",
                    'type' => 'success',
                ]);
            }
        }

        $this->confirmingAreaDeletion = false;
        $this->areaToDeleteId = null;
        $this->areaToDeleteName = '';
    }

    // Acciones Rápidas (Toggles)
    public function toggleAreaQuotation(int $areaId): void
    {
        $area = Area::findOrFail($areaId);
        $area->can_generate_quotation = !$area->can_generate_quotation;
        $area->save();

        $stateText = $area->can_generate_quotation ? 'habilitada' : 'deshabilitada';
        $this->dispatch('notify', message: "Generación de cotización {$stateText} para '{$area->name}'.", type: 'info');
    }

    public function toggleAreaViewAmount(int $areaId): void
    {
        $area = Area::findOrFail($areaId);
        $area->can_view_quotation_amount = !$area->can_view_quotation_amount;
        $area->save();

        $stateText = $area->can_view_quotation_amount ? 'visible' : 'oculto';
        $this->dispatch('notify', message: "Visualización de montos {$stateText} para '{$area->name}'.", type: 'info');
    }

    public function toggleAreaCollectPayment(int $areaId): void
    {
        $area = Area::findOrFail($areaId);
        $area->can_collect_payment = !$area->can_collect_payment;
        $area->save();

        $stateText = $area->can_collect_payment ? 'habilitado' : 'deshabilitado';
        $this->dispatch('notify', message: "Cobro de pagos {$stateText} para '{$area->name}'.", type: 'info');
    }

    public function toggleAreaDelivery(int $areaId): void
    {
        $area = Area::findOrFail($areaId);
        $area->is_delivery = !$area->is_delivery;
        $area->save();

        $stateText = $area->is_delivery ? 'marcada como Área de Entrega' : 'desmarcada de entrega';
        $this->dispatch('notify', message: "'{$area->name}' {$stateText}.", type: 'info');
    }

    public function loadRecommendedAreas(): void
    {
        $tenantId = session('tenant_id') ?: Auth::user()?->tenant_id;
        if (!$tenantId) {
            return;
        }

        $recommended = [
            ['name' => 'Recepción', 'description' => 'Ingreso de vehículos, inventario y hoja de recepción.', 'status' => 'recibido', 'delivery' => false, 'quote' => false, 'view_amount' => true, 'collect' => true],
            ['name' => 'Diagnóstico y Presupuestos', 'description' => 'Evaluación técnica, escaneo y formulación de cotización.', 'status' => 'presupuesto', 'delivery' => false, 'quote' => true, 'view_amount' => true, 'collect' => false],
            ['name' => 'En Espera de Repuestos', 'description' => 'Vehículos en espera de refacciones o autorización del cliente/aseguradora.', 'status' => 'en_espera', 'delivery' => false, 'quote' => false, 'view_amount' => true, 'collect' => false],
            ['name' => 'Mecánica Rápida', 'description' => 'Servicio menor, cambio de aceite, frenos y fluidos.', 'status' => 'trabajando', 'delivery' => false, 'quote' => false, 'view_amount' => false, 'collect' => false],
            ['name' => 'Mecánica Mayor', 'description' => 'Reparación de motor, transmisión, suspensión y dirección.', 'status' => 'trabajando', 'delivery' => false, 'quote' => false, 'view_amount' => false, 'collect' => false],
            ['name' => 'Enderezado y Pintura', 'description' => 'Reparación de carrocería, lijado, aplicación de pintura y pulido.', 'status' => 'trabajando', 'delivery' => false, 'quote' => false, 'view_amount' => false, 'collect' => false],
            ['name' => 'Control de Calidad y Lavado', 'description' => 'Inspección final de pruebas y limpieza previa a entrega.', 'status' => 'revision', 'delivery' => false, 'quote' => false, 'view_amount' => false, 'collect' => false],
            ['name' => 'Entrega', 'description' => 'Área de entrega del vehículo al cliente y liquidación.', 'status' => 'terminado', 'delivery' => true, 'quote' => false, 'view_amount' => true, 'collect' => true],
        ];

        $existingNames = Area::where('tenant_id', $tenantId)
            ->pluck('name')
            ->map(fn($n) => mb_strtolower(trim($n)))
            ->toArray();

        $addedCount = 0;
        $order = (int) Area::where('tenant_id', $tenantId)->max('order');

        foreach ($recommended as $rec) {
            if (!in_array(mb_strtolower($rec['name']), $existingNames)) {
                $order++;
                Area::create([
                    'tenant_id' => $tenantId,
                    'name' => $rec['name'],
                    'description' => $rec['description'],
                    'default_status' => $rec['status'],
                    'is_delivery' => $rec['delivery'],
                    'can_generate_quotation' => $rec['quote'],
                    'can_view_quotation_amount' => $rec['view_amount'],
                    'can_collect_payment' => $rec['collect'],
                    'order' => $order,
                ]);
                $addedCount++;
            }
        }

        $this->dispatch('notify', [
            'message' => $addedCount > 0 ? "Se agregaron {$addedCount} áreas recomendadas al taller." : "Las áreas recomendadas ya se encontraban configuradas.",
            'type' => 'success',
        ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $tenantId = session('tenant_id') ?: Auth::user()?->tenant_id;

        $areas = Area::where('tenant_id', $tenantId)
            ->with(['users'])
            ->withCount('workOrders')
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(10);

        $tenantUsers = User::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('livewire.workshop-settings', [
            'areas' => $areas,
            'tenantUsers' => $tenantUsers,
            'statusOptions' => Area::STATUS_OPTIONS,
            'allVehicleTypes' => Tenant::ALL_VEHICLE_TYPES,
        ]);
    }
}
