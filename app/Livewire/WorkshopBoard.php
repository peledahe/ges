<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Area;
use App\Models\WorkOrder;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;

class WorkshopBoard extends Component
{
    public $areas;
    
    // For Modal Details
    public $selectedOrder = null;
    public $showOrderModal = false;

    // For New Area Modal
    public $showCreateAreaModal = false;
    public $newAreaName = '';

    public function mount()
    {
        $this->refreshBoard();
    }

    #[On('refresh-board')] 
    public function refreshBoard()
    {
        // Load areas with their active work orders
        $query = Area::orderBy('order')->with(['workOrders' => function($query) {
            $query->whereNotIn('status', ['terminado'])
                  ->with('vehicle');
        }]);

        // Filter by Area if user is restricted
        if (auth()->user()->area_id && !auth()->user()->isAdmin()) {
            $query->where('id', auth()->user()->area_id);
        }

        $this->areas = $query->get();
    }

    public function reorderAreas($fromId, $toId)
    {
        $areas = Area::orderBy('order')->get();
        $fromArea = $areas->firstWhere('id', $fromId);
        $toArea = $areas->firstWhere('id', $toId);
        
        if (!$fromArea || !$toArea) return;

        $newOrder = $areas->pluck('id')->toArray();
        $fromIndex = array_search($fromId, $newOrder);
        $toIndex = array_search($toId, $newOrder);

        // Move
        array_splice($newOrder, $fromIndex, 1);
        array_splice($newOrder, $toIndex, 0, $fromId);

        // Update all
        foreach ($newOrder as $index => $id) {
             Area::where('id', $id)->update(['order' => $index]);
        }
        
        $this->refreshBoard();
    }

    public function createArea()
    {
        $this->validate([
            'newAreaName' => 'required|string|max:255',
        ]);

        Area::create([
            'name' => $this->newAreaName,
            'order' => Area::max('order') + 1,
        ]);

        $this->newAreaName = '';
        $this->showCreateAreaModal = false;
        $this->refreshBoard();
        $this->dispatch('notify', 'Nueva área creada correctamente');
    }

    public function updateOrderArea($orderId, $newAreaId)
    {
        $order = WorkOrder::find($orderId);
        
        if ($order && $order->current_area_id != $newAreaId) {
            // Log logic could be added here
            $order->update([
                'current_area_id' => $newAreaId,
            ]);
            
            // Re-fetch to update UI
            $this->refreshBoard();
            
            // Send Notification
            try {
                if ($order->vehicle->owner_email) {
                    $order->vehicle->notify(new \App\Notifications\WorkOrderUpdated($order));
                }
            } catch (\Exception $e) {
                // Log error
            }

            $this->dispatch('notify', 'Orden actualizada y notificación enviada');
        }
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = WorkOrder::with(['vehicle', 'checklists', 'area'])->find($orderId);
        $this->showOrderModal = true;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.workshop-board');
    }
}
