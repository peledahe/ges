<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class WorkshopBoard extends Component
{
    use WithPagination;

    public $areas = [];

    // Detalle de Orden
    public $selectedOrder = null;
    public $showOrderModal = false;

    // Modal Crear Nueva Área
    public $showCreateAreaModal = false;
    public $newAreaName = '';
    public $newAreaStatus = 'trabajando';
    public $newAreaIsDelivery = false;
    public bool $newAreaCanGenerateQuotation = false;
    public bool $newAreaCanViewQuotationAmount = true;
    public bool $newAreaCanCollectPayment = false;

    // Edición de Cotización dentro del Modal de Detalle
    public bool $editingQuotation = false;
    public string $quotationTotalAmount = '';
    public string $quotationNotes = '';

    // Modal Registro de Pago
    public bool $showPaymentModal = false;
    public ?int $paymentOrderId = null;
    public ?WorkOrder $paymentOrder = null;
    public $paymentTotalAmount = '';
    public $paymentPaidAmount = '';
    public string $paymentMethod = 'efectivo';
    public string $paymentReference = '';
    public string $paymentStatus = 'pendiente';

    // Modal de Confirmación de Entrega
    public bool $showDeliveryModal = false;
    public ?int $deliveryOrderId = null;
    public ?WorkOrder $deliveryOrder = null;

    // Modal de Historial de Entregados
    public bool $showDeliveredModal = false;

    public function mount()
    {
        $this->refreshBoard();
    }

    #[On('refresh-board')]
    public function refreshBoard(): void
    {
        $user = Auth::user();

        // Consulta de áreas activas con sus órdenes en proceso
        // Las órdenes con status 'entregado' no se muestran en el Kanban (desaparecen al entregarse)
        $query = Area::orderBy('order')->with(['workOrders' => function ($q) {
            $q->whereNotIn('status', ['entregado'])
              ->with('vehicle');
        }]);

        // Filtrar columnas visibles si el usuario tiene asignadas áreas específicas
        $accessibleIds = $user ? $user->accessibleAreaIds() : null;
        if ($accessibleIds !== null) {
            $query->whereIn('id', $accessibleIds);
        }

        $this->areas = $query->get();
    }

    public function updateAreaOrders(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Area::where('id', $id)->update(['order' => $index]);
        }
        $this->refreshBoard();
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

    public function createArea(): void
    {
        $this->validate([
            'newAreaName' => 'required|string|min:2|max:255',
            'newAreaStatus' => 'required|string',
        ]);

        $status = $this->newAreaStatus ?: Area::suggestStatusFromName($this->newAreaName);
        $isDelivery = $this->newAreaIsDelivery || str_contains(mb_strtolower($this->newAreaName), 'entreg');

        Area::create([
            'tenant_id' => session('tenant_id') ?: Auth::user()?->tenant_id,
            'name' => trim($this->newAreaName),
            'default_status' => $status,
            'is_delivery' => $isDelivery,
            'can_generate_quotation' => $this->newAreaCanGenerateQuotation,
            'can_view_quotation_amount' => $this->newAreaCanViewQuotationAmount,
            'can_collect_payment' => $this->newAreaCanCollectPayment,
            'order' => (int) Area::max('order') + 1,
        ]);

        $this->reset(['newAreaName', 'newAreaStatus', 'newAreaIsDelivery', 'newAreaCanGenerateQuotation', 'newAreaCanViewQuotationAmount', 'newAreaCanCollectPayment']);
        $this->newAreaCanViewQuotationAmount = true;
        $this->showCreateAreaModal = false;
        $this->refreshBoard();
        $this->dispatch('notify', message: 'Nueva área creada correctamente con sus permisos.', type: 'success');
    }

    /**
     * Mueve una orden a una nueva área y actualiza su estado automáticamente
     * según el área correspondiente en el taller.
     */
    public function updateOrderArea(int|string $orderId, int|string $newAreaId): void
    {
        $order = WorkOrder::with('vehicle')->find($orderId);
        $targetArea = Area::find($newAreaId);

        if (!$order || !$targetArea) {
            return;
        }

        if ((int) $order->current_area_id !== (int) $newAreaId) {
            // Determinar automáticamente el estado que corresponde a esta área
            $newStatus = $targetArea->default_status ?: Area::suggestStatusFromName($targetArea->name);

            $order->update([
                'current_area_id' => $newAreaId,
                'status' => $newStatus,
            ]);

            $this->refreshBoard();

            // Notificación por correo opcional
            try {
                if ($order->vehicle?->owner_email) {
                    $order->vehicle->notify(new \App\Notifications\WorkOrderUpdated($order));
                }
            } catch (\Exception $e) {
                // Silently continue
            }

            $statusText = Area::STATUS_OPTIONS[$newStatus] ?? ucfirst($newStatus);
            $plate = $order->vehicle?->plate ?? "#{$order->id}";

            $this->dispatch('notify', [
                'message' => "Vehículo {$plate} movido a '{$targetArea->name}'. Estado actualizado a: {$statusText}.",
                'type' => 'success',
            ]);
        }
    }

    public function selectOrder(int|string $orderId): void
    {
        $this->selectedOrder = WorkOrder::with(['vehicle', 'checklists', 'area', 'photos'])->find($orderId);
        if ($this->selectedOrder) {
            $this->quotationTotalAmount = $this->selectedOrder->total_amount !== null ? (string) $this->selectedOrder->total_amount : '';
            $this->quotationNotes = $this->selectedOrder->notes ?? '';
            $this->editingQuotation = false;
        }
        $this->showOrderModal = true;
    }

    public function editQuotation(): void
    {
        $this->editingQuotation = true;
    }

    public function cancelEditQuotation(): void
    {
        if ($this->selectedOrder) {
            $this->quotationTotalAmount = $this->selectedOrder->total_amount !== null ? (string) $this->selectedOrder->total_amount : '';
            $this->quotationNotes = $this->selectedOrder->notes ?? '';
        }
        $this->editingQuotation = false;
    }

    public function saveQuotation(): void
    {
        if (!$this->selectedOrder) {
            return;
        }

        if (!$this->canGenerateQuotationForOrder($this->selectedOrder)) {
            $this->dispatch('notify', message: 'El área actual no tiene permiso para generar cotizaciones.', type: 'warning');
            return;
        }

        $this->validate([
            'quotationTotalAmount' => 'nullable|numeric|min:0',
            'quotationNotes' => 'nullable|string|max:2000',
        ]);

        $total = $this->quotationTotalAmount !== '' ? (float) $this->quotationTotalAmount : null;

        $this->selectedOrder->update([
            'total_amount' => $total,
            'notes' => $this->quotationNotes ?: null,
        ]);

        $this->selectedOrder = $this->selectedOrder->fresh(['vehicle', 'checklists', 'area', 'photos']);
        $this->editingQuotation = false;
        $this->refreshBoard();
        $this->dispatch('notify', message: "Cotización de la orden #{$this->selectedOrder->id} guardada exitosamente.", type: 'success');
    }

    public function canViewOrderQuotationAmount(?WorkOrder $order): bool
    {
        if (!$order) return false;
        $user = Auth::user();
        if ($user?->isAdmin()) return true;
        if (!$order->area) return true;
        return (bool) $order->area->can_view_quotation_amount;
    }

    public function canGenerateQuotationForOrder(?WorkOrder $order): bool
    {
        if (!$order) return false;
        $user = Auth::user();
        if ($user?->isAdmin()) return true;
        if (!$order->area) return false;
        return (bool) $order->area->can_generate_quotation;
    }

    public function canCollectPaymentForOrder(?WorkOrder $order): bool
    {
        if (!$order) return false;
        $user = Auth::user();
        if ($user?->isAdmin()) return true;
        if (!$order->area) return true;
        return (bool) $order->area->can_collect_payment;
    }

    public function getQuotationArea(): ?Area
    {
        return Area::where('can_generate_quotation', true)->first();
    }

    // GESTIÓN DE PAGOS
    public function openPaymentModal(int|string $orderId): void
    {
        $this->paymentOrderId = (int) $orderId;
        $this->paymentOrder = WorkOrder::with('vehicle')->findOrFail($orderId);

        $this->paymentTotalAmount = $this->paymentOrder->total_amount ?? '';
        $this->paymentPaidAmount = $this->paymentOrder->paid_amount ?? '';
        $this->paymentMethod = $this->paymentOrder->payment_method ?? 'efectivo';
        $this->paymentReference = $this->paymentOrder->payment_reference ?? '';
        $this->paymentStatus = $this->paymentOrder->payment_status ?? 'pendiente';

        $this->showOrderModal = false;
        $this->showPaymentModal = true;
    }

    public function savePayment(): void
    {
        $this->validate([
            'paymentTotalAmount' => 'nullable|numeric|min:0',
            'paymentPaidAmount' => 'nullable|numeric|min:0',
            'paymentMethod' => 'required|string',
            'paymentReference' => 'nullable|string|max:255',
        ]);

        if ($this->paymentOrderId) {
            $order = WorkOrder::findOrFail($this->paymentOrderId);

            $total = (float) $this->paymentTotalAmount;
            $paid = (float) $this->paymentPaidAmount;

            // Determinar estado de pago de forma inteligente
            if ($total > 0 && $paid >= $total) {
                $status = 'pagado';
            } elseif ($paid > 0 && $paid < $total) {
                $status = 'parcial';
            } else {
                $status = $this->paymentStatus ?: 'pendiente';
            }

            $order->update([
                'total_amount' => $this->paymentTotalAmount !== '' ? $total : null,
                'paid_amount' => $this->paymentPaidAmount !== '' ? $paid : null,
                'payment_status' => $status,
                'payment_method' => $this->paymentMethod,
                'payment_reference' => $this->paymentReference ?: null,
                'paid_at' => $paid > 0 ? now() : null,
            ]);

            $this->showPaymentModal = false;
            $this->refreshBoard();
            $this->dispatch('notify', message: "Pago de la orden #{$order->id} registrado correctamente.", type: 'success');
        }
    }

    // ENTREGA DE VEHÍCULO
    public function openDeliveryModal(int|string $orderId): void
    {
        $this->deliveryOrderId = (int) $orderId;
        $this->deliveryOrder = WorkOrder::with(['vehicle', 'area'])->findOrFail($orderId);

        $this->showOrderModal = false;
        $this->showDeliveryModal = true;
    }

    public function confirmDelivery(): void
    {
        if ($this->deliveryOrderId) {
            $order = WorkOrder::with('vehicle')->findOrFail($this->deliveryOrderId);

            $plate = $order->vehicle?->plate ?? "#{$order->id}";

            // Cambiar a estatus 'entregado', registrar fecha de entrega
            // y desaparece del Kanban automáticamente
            $order->update([
                'status' => 'entregado',
                'delivered_at' => now(),
            ]);

            $this->showDeliveryModal = false;
            $this->refreshBoard();

            $this->dispatch('notify', message: "🚗 Vehículo {$plate} entregado exitosamente. La orden se ha archivado del tablero.", type: 'success');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $deliveredOrders = $this->showDeliveredModal
            ? WorkOrder::where('status', 'entregado')
                ->with(['vehicle', 'area'])
                ->latest('delivered_at')
                ->paginate(10, ['*'], 'deliveredPage')
            : collect();

        return view('livewire.workshop-board', [
            'deliveredOrders' => $deliveredOrders,
            'statusOptions' => Area::STATUS_OPTIONS,
        ]);
    }
}
