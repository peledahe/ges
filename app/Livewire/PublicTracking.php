<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Livewire\Attributes\Layout;

class PublicTracking extends Component
{
    public $plate;
    public $workOrder;
    public $searched = false;

    // Use a simple layout without auth guards
    #[Layout('layouts.guest')] 
    public function render()
    {
        return view('livewire.public-tracking');
    }

    public function search()
    {
        $this->validate([
            'plate' => 'required|min:3',
        ]);

        $this->searched = true;

        // We find the LATEST active work order for this vehicle.
        // Note: GlobalScope TenantScope might interfere if we are not in a tenant context.
        // However, if we put this route inside a 'check_tenant_slug' middleware, it will be set.
        // If we want a global search, we need to remove the GlobalScope.
        
        $vehicle = Vehicle::withoutGlobalScopes()
            ->where('plate', $this->plate)
            ->first();

        if ($vehicle) {
            $this->workOrder = WorkOrder::withoutGlobalScopes()
                ->where('vehicle_id', $vehicle->id)
                ->whereNotIn('status', ['entregado']) // Show active or recently finished
                ->with(['vehicle', 'area', 'checklists'])
                ->latest()
                ->first();
                
                // If no active, maybe show last delivered?
                 if (!$this->workOrder) {
                    $this->workOrder = WorkOrder::withoutGlobalScopes()
                        ->where('vehicle_id', $vehicle->id)
                        ->latest()
                        ->first();
                 }
        } else {
            $this->workOrder = null;
        }
    }
}
