<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Area;
use App\Models\WorkOrder;
use Livewire\Attributes\On;

class WorkshopBoard extends Component
{
    public $areas;
    
    // For Modal Details
    public $selectedOrder = null;
    public $showOrderModal = false;

    public function mount()
    {
        $this->refreshBoard();
    }

    #[On('refresh-board')] 
    public function refreshBoard()
    {
        // Load areas with their active work orders
        // Use logic to order areas if needed (e.g. by ID or a sort_order field if we add one)
        $this->areas = Area::with(['workOrders' => function($query) {
            $query->whereNotIn('status', ['finalizado', 'entregado'])
                  ->with('vehicle');
        }])->get();
    }

    public function updateOrderArea($orderId, $newAreaId)
    {
        $order = WorkOrder::find($orderId);
        
        if ($order && $order->current_area_id != $newAreaId) {
            // Log logic could be added here
            $order->update([
                'current_area_id' => $newAreaId,
                // Maybe update status based on area? 
                // e.g. if moved to "Entregas" -> status = finalizado?
                // For now keep status manual or mapping logic
            ]);
            
            // Re-fetch to update UI
            $this->refreshBoard();
            
            // Send Notification (Optional, maybe checkbox later, for now auto-send if not same area)
            try {
                if ($order->vehicle->owner_email) {
                    $order->vehicle->notify(new \App\Notifications\WorkOrderUpdated($order));
                }
            } catch (\Exception $e) {
                // Log error but don't break UI
            }

            $this->dispatch('notify', 'Orden actualizada y notificación enviada');
        }
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = WorkOrder::with(['vehicle', 'checklists', 'area'])->find($orderId);
        $this->showOrderModal = true;
    }

    public function render()
    {
        return view('livewire.workshop-board');
    }
}
