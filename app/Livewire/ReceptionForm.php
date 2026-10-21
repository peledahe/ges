<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\ChecklistTemplate;
use App\Models\Customer;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class ReceptionForm extends Component
{
    use WithFileUploads;

    protected $rules = [
        'photos.*' => 'image|max:12288', // 12MB
    ];

    public $currentStep = 1;
    public $totalSteps = 4;

    // Step 1: Customer Info (Identificable por teléfono, correo o NIT)
    public $customer_id;
    public $search_customer = '';
    public $customer_search_results = [];
    public $customer_vehicles = [];
    public $customer_repair_count = 0;
    public $is_existing_customer = false;

    public $owner_name;
    public $owner_phone;
    public $owner_email;
    public $owner_nit;
    public $owner_address;
    public $client_user_status = 'inactivo'; // 'inactivo' o 'activo' (por default inactivo, solo para clientes preferenciales)

    // Step 1: Vehicle Info
    public $plate;
    public $search_plate;
    public $vehicle_id;
    public $type; // Vehicle Type
    public $brand, $model, $line, $color, $year, $vin, $engine_number, $chassis_number;
    public $doors_qty = 4;
    public $fuel_type = 'gasolina'; // gasolina, diesel, electrico
    public $mileage, $fuel_level;

    // Step 2: Checklist
    public $checklistTemplate;
    public $checklistItems = []; // ['group' => ['item' => 'status']]

    // Step 3: Photos & Damages
    public $photos = [];
    public $damageMap; // Base64 image from canvas
    public $damages = []; // Stores marked damages on the diagram

    // Step 4: Review & Confirm
    public $notes;

    public function mount()
    {
        $this->initializeChecklistForType($this->type);
    }

    public function initializeChecklistForType(?string $type = null)
    {
        $tenantId = session('tenant_id') ?? (auth()->check() ? auth()->user()->tenant_id : null);
        $templateSections = ChecklistTemplate::getItemsForVehicleType($type ?: $this->type, $tenantId);

        $newChecklist = [];
        foreach ($templateSections as $section) {
            $secName = $section['section'];
            foreach ($section['items'] as $item) {
                $newChecklist[$secName][$item] = $this->checklistItems[$secName][$item] ?? 'correct';
            }
        }

        $this->checklistItems = $newChecklist;
    }

    /**
     * Búsqueda de cliente por teléfono, correo, NIT o nombre.
     */
    public function searchCustomer()
    {
        $this->validate(['search_customer' => 'required|min:2'], [
            'search_customer.required' => 'Ingresa un número de teléfono, correo, NIT o nombre para buscar.',
            'search_customer.min' => 'Ingresa al menos 2 caracteres para realizar la búsqueda.',
        ]);

        $results = Customer::search($this->search_customer)->take(6)->get();

        if ($results->count() === 1) {
            $this->selectCustomer($results->first()->id);
            $this->customer_search_results = [];
        } elseif ($results->count() > 1) {
            $this->customer_search_results = $results->toArray();
            $this->dispatch('notify', [
                'message' => "Se encontraron {$results->count()} clientes coincidentes. Selecciona el indicado.",
                'type' => 'info'
            ]);
        } else {
            $this->customer_search_results = [];
            $this->customer_id = null;
            $this->is_existing_customer = false;
            $this->customer_vehicles = [];
            $this->customer_repair_count = 0;

            // Intentar sugerir los datos según formato de búsqueda
            if (filter_var($this->search_customer, FILTER_VALIDATE_EMAIL)) {
                $this->owner_email = $this->search_customer;
            } elseif (preg_match('/^[0-9\-\+\s]{7,}$/', $this->search_customer)) {
                $this->owner_phone = $this->search_customer;
            } else {
                $this->owner_name = $this->search_customer;
            }

            $this->dispatch('notify', [
                'message' => 'No se encontró ningún cliente previo con esos datos. Puedes registrarlo como nuevo cliente.',
                'type' => 'warning'
            ]);
        }
    }

    /**
     * Seleccionar cliente encontrado y cargar sus vehículos e historial.
     */
    public function selectCustomer($id)
    {
        $customer = Customer::with(['vehicles', 'workOrders'])->find($id);
        if (!$customer) return;

        $this->customer_id = $customer->id;
        $this->owner_name = $customer->name;
        $this->owner_phone = $customer->phone;
        $this->owner_email = $customer->email;
        $this->owner_nit = $customer->nit;
        $this->owner_address = $customer->address;
        $this->customer_vehicles = $customer->vehicles->toArray();
        $this->customer_repair_count = $customer->workOrders->count();
        $this->is_existing_customer = true;
        $this->customer_search_results = [];

        // Verificar si ya tiene usuario activo en el sistema
        if (!empty($customer->email)) {
            $clientUser = \App\Models\User::withoutGlobalScopes()->where('email', trim($customer->email))->first();
            $this->client_user_status = ($clientUser && $clientUser->isActive()) ? 'activo' : 'inactivo';
        } else {
            $this->client_user_status = 'inactivo';
        }

        $this->dispatch('notify', [
            'message' => "Cliente cargado: {$customer->name} ({$customer->vehicles->count()} vehículos, {$this->customer_repair_count} reparaciones previas).",
            'type' => 'success'
        ]);
    }

    /**
     * Limpiar cliente actual para registrar uno nuevo.
     */
    public function resetCustomer()
    {
        $this->reset([
            'customer_id',
            'search_customer',
            'customer_search_results',
            'customer_vehicles',
            'customer_repair_count',
            'owner_name',
            'owner_phone',
            'owner_email',
            'owner_nit',
            'owner_address',
            'client_user_status',
            'is_existing_customer'
        ]);
        $this->client_user_status = 'inactivo';

        $this->dispatch('notify', [
            'message' => 'Formulario de cliente restablecido para nuevo registro.',
            'type' => 'info'
        ]);
    }

    /**
     * Alternar el estado de acceso de usuario para el cliente (activo/inactivo).
     */
    public function toggleClientUserStatus(): void
    {
        $this->client_user_status = ($this->client_user_status === 'activo') ? 'inactivo' : 'activo';
    }

    /**
     * Seleccionar un vehículo ya registrado perteneciente al cliente.
     */
    public function selectCustomerVehicle($vehicleId)
    {
        $vehicle = Vehicle::find($vehicleId);
        if (!$vehicle) return;

        $this->vehicle_id = $vehicle->id;
        $this->plate = $vehicle->plate;
        $this->type = $vehicle->type;
        $this->brand = $vehicle->brand;
        $this->model = $vehicle->model;
        $this->line = $vehicle->line;
        $this->doors_qty = in_array($vehicle->type, ['pasola', 'moto']) ? 0 : ($vehicle->doors_qty ?: 4);
        $this->color = $vehicle->color;
        $this->year = $vehicle->year;
        $this->vin = $vehicle->vin;
        $this->fuel_type = $vehicle->fuel_type ?: 'gasolina';

        $this->initializeChecklistForType($vehicle->type);

        $this->dispatch('notify', [
            'message' => "Vehículo cargado: {$vehicle->brand} {$vehicle->model} [{$vehicle->plate}].",
            'type' => 'success'
        ]);
    }

    public function searchVehicle($plateNumber = null)
    {
        $term = $plateNumber ?: ($this->plate ?: $this->search_plate);
        $this->search_plate = $term;

        if (empty($term) || strlen(trim($term)) < 3) {
            $this->addError('plate', 'Ingresa una placa válida (mínimo 3 caracteres) para buscar.');
            return;
        }
        
        $vehicle = Vehicle::where('plate', strtoupper(trim($term)))->first();

        if ($vehicle) {
            $this->vehicle_id = $vehicle->id;
            $this->plate = $vehicle->plate;
            $this->type = $vehicle->type;
            $this->brand = $vehicle->brand;
            $this->model = $vehicle->model;
            $this->line = $vehicle->line;
            $this->doors_qty = in_array($vehicle->type, ['pasola', 'moto']) ? 0 : ($vehicle->doors_qty ?: 4);
            $this->color = $vehicle->color;
            $this->year = $vehicle->year;
            $this->vin = $vehicle->vin;
            $this->fuel_type = $vehicle->fuel_type ?: 'gasolina';

            $this->initializeChecklistForType($vehicle->type);

            // Si el vehículo tiene cliente y no hay cliente seleccionado, autoseleccionar cliente
            if ($vehicle->customer_id && !$this->customer_id) {
                $this->selectCustomer($vehicle->customer_id);
            } elseif (!$this->customer_id) {
                $this->owner_name = $vehicle->owner_name;
                $this->owner_phone = $vehicle->owner_phone;
                $this->owner_email = $vehicle->owner_email;
                $this->owner_nit = $vehicle->owner_nit;
                $this->owner_address = $vehicle->owner_address;
            }

            $this->dispatch('notify', [
                'message' => "Vehículo encontrado: {$vehicle->brand} {$vehicle->model} [{$vehicle->plate}].",
                'type' => 'success'
            ]);
        } else {
            $this->vehicle_id = null;
            $this->plate = strtoupper(trim($term));
            $this->doors_qty = in_array($this->type, ['pasola', 'moto']) ? 0 : ($this->doors_qty ?: 4);

            $this->dispatch('notify', [
                'message' => "Placa '{$this->plate}' no registrada. Puedes completar los datos del nuevo vehículo.",
                'type' => 'info'
            ]);
        }
    }

    public function updatedType($value)
    {
        if (in_array($value, ['pasola', 'moto'])) {
            $this->doors_qty = 0;
        } elseif ($this->doors_qty === 0 || $this->doors_qty === '0') {
            $this->doors_qty = 4;
        }

        $this->initializeChecklistForType($value);
    }

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            if (in_array($this->type, ['pasola', 'moto']) && ($this->doors_qty === null || $this->doors_qty === '')) {
                $this->doors_qty = 0;
            }

            $rules = [
                'owner_name' => 'required|min:3',
                'plate' => 'required|min:3',
                'type' => 'required',
                'brand' => 'required',
                'model' => 'required',
                'doors_qty' => in_array($this->type, ['pasola', 'moto']) ? 'nullable|integer|min:0|max:5' : 'required|integer|min:2|max:5',
                'mileage' => 'required|numeric',
                'fuel_level' => 'required',
            ];

            if ($this->client_user_status === 'activo') {
                $rules['owner_email'] = 'required|email';
            }

            $this->validate($rules, [
                'owner_name.required' => 'El nombre del cliente es obligatorio.',
                'owner_email.required' => 'Para habilitar el acceso al portal del cliente preferencial, debes ingresar un correo electrónico.',
                'owner_email.email' => 'Ingresa un formato de correo electrónico válido.',
                'plate.required' => 'La placa del vehículo es obligatoria.',
                'type.required' => 'Selecciona el tipo de vehículo.',
                'brand.required' => 'La marca del vehículo es obligatoria.',
                'model.required' => 'El modelo del vehículo es obligatorio.',
                'mileage.required' => 'Ingresa el kilometraje actual.',
                'fuel_level.required' => 'Selecciona el nivel de combustible.',
            ]);
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit()
    {
        $this->validate([
            'photos.*' => 'image|max:12288', // Validate before processing
        ]);

        DB::transaction(function () {
            // 1. Save / Update Customer
            $customer = null;
            if ($this->customer_id) {
                $customer = Customer::find($this->customer_id);
            }

            if (!$customer) {
                // Prevenir duplicados si coincide teléfono, email o nit
                if (!empty($this->owner_nit)) {
                    $customer = Customer::where('nit', trim($this->owner_nit))->first();
                }
                if (!$customer && !empty($this->owner_phone)) {
                    $customer = Customer::where('phone', trim($this->owner_phone))->first();
                }
                if (!$customer && !empty($this->owner_email)) {
                    $customer = Customer::where('email', trim($this->owner_email))->first();
                }
            }

            if ($customer) {
                $customer->update([
                    'name' => $this->owner_name,
                    'phone' => $this->owner_phone,
                    'email' => $this->owner_email,
                    'nit' => $this->owner_nit,
                    'address' => $this->owner_address,
                ]);
            } else {
                $tenantId = session('tenant_id') ?? (auth()->check() ? auth()->user()->tenant_id : null);
                $customer = Customer::create([
                    'tenant_id' => $tenantId,
                    'name' => $this->owner_name,
                    'phone' => $this->owner_phone,
                    'email' => $this->owner_email,
                    'nit' => $this->owner_nit,
                    'address' => $this->owner_address,
                ]);
            }

            // 2. Save / Update Vehicle asociado al cliente
            $vehicle = Vehicle::updateOrCreate(
                ['plate' => strtoupper(trim($this->plate))],
                [
                    'customer_id' => $customer->id,
                    'type' => $this->type,
                    'brand' => $this->brand,
                    'model' => $this->model,
                    'line' => $this->line,
                    'doors_qty' => $this->doors_qty,
                    'color' => $this->color,
                    'year' => $this->year,
                    'vin' => $this->vin,
                    'fuel_type' => $this->fuel_type ?: 'gasolina',
                    'owner_name' => $customer->name,
                    'owner_phone' => $customer->phone,
                    'owner_email' => $customer->email,
                    'owner_nit' => $customer->nit,
                    'owner_address' => $customer->address,
                ]
            );

            // Crear o habilitar usuario de acceso para el cliente (solo si fue marcado como activo para clientes preferenciales)
            if ($this->client_user_status === 'activo' && !empty($this->owner_email)) {
                $tenantId = session('tenant_id') ?? (auth()->check() ? auth()->user()->tenant_id : $customer->tenant_id);
                $userEmail = trim($this->owner_email);
                $existingUser = \App\Models\User::withoutGlobalScopes()->where('email', $userEmail)->first();
                
                $rawPassword = !empty($this->owner_phone) ? preg_replace('/[^0-9]/', '', $this->owner_phone) : strtoupper(trim($this->plate));
                if (strlen($rawPassword) < 8) {
                    $rawPassword = str_pad($rawPassword, 8, '0');
                }

                if ($existingUser) {
                    $existingUser->update([
                        'name' => $customer->name ?: $existingUser->name,
                        'tenant_id' => $tenantId ?: $existingUser->tenant_id,
                        'role' => 'client',
                        'is_active' => true,
                    ]);
                } else {
                    \App\Models\User::create([
                        'name' => $customer->name ?: 'Cliente Preferencial',
                        'email' => $userEmail,
                        'password' => \Illuminate\Support\Facades\Hash::make($rawPassword),
                        'role' => 'client',
                        'tenant_id' => $tenantId,
                        'is_active' => true,
                    ]);
                }
            }

            // 2. Create Work Order
            // Find "Recepción" area or default to first area
            $receptionArea = \App\Models\Area::where('name', 'like', 'Recepci%')->first();
            $areaId = $receptionArea ? $receptionArea->id : \App\Models\Area::first()->id;

            $order = WorkOrder::create([
                'vehicle_id' => $vehicle->id,
                'status' => 'recibido',
                'current_area_id' => $areaId,
                'received_at' => now(),
                'mileage' => $this->mileage,
                'fuel_level' => $this->fuel_level,
                'fuel_type' => $this->fuel_type ?: 'gasolina',
                'notes' => $this->notes,
                'payment_type' => 'particular',
            ]);

            // 3. Save Checklist
            foreach ($this->checklistItems as $group => $items) {
                foreach ($items as $item => $status) {
                    $order->checklists()->create([
                        'group_name' => $group,
                        'item_name' => $item,
                        'status' => $status,
                    ]);
                }
            }

            // 4. Save Photos
            // Save uploaded photos
            if ($this->photos) {
                foreach ($this->photos as $photo) {
                    $path = $photo->store('work-orders/' . $order->id, 'public');
                    $order->photos()->create([
                        'path' => $path,
                        'category' => 'general',
                    ]);
                }
            }

            // Save Damage Map
            if ($this->damageMap) {
                // Decode base64
                $image = str_replace('data:image/png;base64,', '', $this->damageMap);
                $image = str_replace(' ', '+', $image);
                $imageName = 'damage_map_' . time() . '.png';
                $path = 'work-orders/' . $order->id . '/' . $imageName;
                
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, base64_decode($image));

                $order->photos()->create([
                    'path' => $path,
                    'category' => 'damage_map',
                ]);
            }
            
            // 5. Notify/Log
            try {
                if ($vehicle->owner_email) {
                    $vehicle->notify(new \App\Notifications\OrderReceived($order));
                }
            } catch (\Exception $e) {
                // Log error but continue
                \Illuminate\Support\Facades\Log::error('Error sending email: ' . $e->getMessage());
            }

            session()->flash('message', 'Orden de recepción creada exitosamente: #'. $order->id);
            return redirect()->route('dashboard');
        });
    }

    public function getVehicleImageProperty()
    {
        $images = [
            'pasola' => 'pasola.png',
            'moto' => 'moto.png',
            'hashback' => 'hashback.png',
            'sedan' => 'sedan.png',
            'suv' => 'camioneta.png',
            'pickup' => 'pickup.png',
            'pickup_double' => 'pickup-doble-cabina.png',
            'van' => 'van.png',
            'camion' => 'camion.png',
            'cabezal' => 'cabezal.png',
            'bus_escolar' => 'bus-escolar.png',
            'bus' => 'bus.png',
        ];

        return $images[$this->type] ?? null;
    }

    public function getAllowedVehicleTypesProperty(): array
    {
        $tenantId = session('tenant_id');
        $tenant = $tenantId ? \App\Models\Tenant::find($tenantId) : auth()->user()?->tenant;

        if ($tenant) {
            return $tenant->getVehicleTypes();
        }

        return \App\Models\Tenant::ALL_VEHICLE_TYPES;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.reception-form', [
            'allowedVehicleTypes' => $this->allowedVehicleTypes,
        ]);
    }
}
