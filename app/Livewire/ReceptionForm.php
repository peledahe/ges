<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\ChecklistTemplate;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class ReceptionForm extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 4;

    // Step 1: Vehicle & Owner
    public $plate;
    public $search_plate;
    public $vehicle_id;
    public $type; // Vehicle Type
    public $brand, $model, $line, $color, $year, $vin, $engine_number, $chassis_number;
    public $owner_name, $owner_phone, $owner_email, $owner_address;
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
        // Load default template for the tenant
        // For MVP we assume first template or create a default one if missing
        $this->checklistTemplate = ChecklistTemplate::first();
        if ($this->checklistTemplate) {
           $this->initializeChecklist();
        }
    }

    public function initializeChecklist()
    {
        $templateItems = json_decode($this->checklistTemplate->items, true);
        foreach ($templateItems as $section) {
            foreach ($section['items'] as $item) {
                $this->checklistItems[$section['section']][$item] = 'correct'; // Default status
            }
        }
    }

    public function searchVehicle()
    {
        $this->validate(['search_plate' => 'required|min:3']);
        
        $vehicle = Vehicle::where('plate', $this->search_plate)->first();

        if ($vehicle) {
            $this->vehicle_id = $vehicle->id;
            $this->plate = $vehicle->plate;
            $this->type = $vehicle->type;
            $this->brand = $vehicle->brand;
            $this->model = $vehicle->model;
            $this->line = $vehicle->line;
            $this->color = $vehicle->color;
            $this->year = $vehicle->year;
            $this->vin = $vehicle->vin;
            $this->owner_name = $vehicle->owner_name;
            $this->owner_phone = $vehicle->owner_phone;
            $this->owner_email = $vehicle->owner_email;
            $this->owner_address = $vehicle->owner_address;
        } else {
            // New Vehicle Mode
            $this->reset(['vehicle_id', 'type', 'brand', 'model', 'line', 'color', 'year', 'vin', 'owner_name', 'owner_phone', 'owner_email']);
            $this->plate = $this->search_plate;
        }
    }

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            $this->validate([
                'plate' => 'required',
                'type' => 'required',
                'brand' => 'required',
                'model' => 'required',
                'owner_name' => 'required',
                'mileage' => 'required|numeric',
                'fuel_level' => 'required',
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
        DB::transaction(function () {
            // 1. Save/Update Vehicle
            $vehicle = Vehicle::updateOrCreate(
                ['plate' => $this->plate],
                [
                    'type' => $this->type,
                    'brand' => $this->brand,
                    'model' => $this->model,
                    'line' => $this->line,
                    'color' => $this->color,
                    'year' => $this->year,
                    'vin' => $this->vin,
                    'owner_name' => $this->owner_name,
                    'owner_phone' => $this->owner_phone,
                    'owner_email' => $this->owner_email,
                    'owner_address' => $this->owner_address,
                ]
            );

            // 2. Create Work Order
            $order = WorkOrder::create([
                'vehicle_id' => $vehicle->id,
                'status' => 'recepcion',
                'received_at' => now(),
                'mileage' => $this->mileage,
                'fuel_level' => $this->fuel_level,
                // 'payment_type' => ... (can add this field later to form)
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
            session()->flash('message', 'Orden de recepción creada exitosamente: #'. $order->id);
            return redirect()->route('dashboard');
        });
    }

    public function getVehicleImageProperty()
    {
        $images = [
            'sedan' => 'sedan.png',
            'hashback' => 'hashback.png',
            'suv' => 'camioneta.png',
            'pickup' => 'pickup.png',
            'pickup_double' => 'pickup-doble-cabina.png',
        ];

        return $images[$this->type] ?? null;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.reception-form');
    }
}
