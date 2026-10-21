<?php

namespace App\Livewire;

use App\Helpers\CurrencyHelper;
use App\Models\Area;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ReportCenter extends Component
{
    use WithPagination;

    // Filtros de Reporte
    public string $reportType = 'general'; // 'general', 'pagos', 'trabajos', 'cotizaciones'
    public string $period = 'month'; // 'today', 'week', 'month', 'year', 'custom'
    public ?string $startDate = null;
    public ?string $endDate = null;
    public string $statusFilter = 'all'; // all, recibido, presupuesto, trabajando, terminado, entregado
    public string $paymentStatusFilter = 'all'; // all, pagado, parcial, pendiente
    public string $paymentMethodFilter = 'all'; // all, efectivo, tarjeta, transferencia, cheque, aseguradora, otro
    public string $areaFilter = 'all'; // all o id de área
    public string $search = '';

    public function mount(): void
    {
        $this->setDefaultDates();
    }

    public function updatedPeriod($value): void
    {
        $this->setDefaultDates();
        $this->resetPage();
    }

    public function updatedReportType(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPaymentStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPaymentMethodFilter(): void
    {
        $this->resetPage();
    }

    public function updatedAreaFilter(): void
    {
        $this->resetPage();
    }

    private function setDefaultDates(): void
    {
        $now = Carbon::now();
        switch ($this->period) {
            case 'today':
                $this->startDate = $now->copy()->startOfDay()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfDay()->format('Y-m-d');
                break;
            case 'week':
                $this->startDate = $now->copy()->startOfWeek()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfWeek()->format('Y-m-d');
                break;
            case 'month':
                $this->startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfMonth()->format('Y-m-d');
                break;
            case 'year':
                $this->startDate = $now->copy()->startOfYear()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfYear()->format('Y-m-d');
                break;
            case 'custom':
                if (empty($this->startDate)) {
                    $this->startDate = $now->copy()->subMonth()->format('Y-m-d');
                }
                if (empty($this->endDate)) {
                    $this->endDate = $now->format('Y-m-d');
                }
                break;
        }
    }

    private function getBaseQuery()
    {
        $query = WorkOrder::with(['vehicle.customer', 'area']);

        // Rango de fechas
        if ($this->startDate && $this->endDate) {
            $start = Carbon::parse($this->startDate)->startOfDay();
            $end = Carbon::parse($this->endDate)->endOfDay();

            if ($this->reportType === 'pagos') {
                // Filtrar por fecha de pago o actualización
                $query->whereBetween('updated_at', [$start, $end]);
            } else {
                $query->whereBetween('created_at', [$start, $end]);
            }
        }

        // Búsqueda por placa, cliente o marca
        if (!empty($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->whereHas('vehicle', function ($vq) use ($term) {
                    $vq->where('plate', 'like', "%{$term}%")
                       ->orWhere('brand', 'like', "%{$term}%")
                       ->orWhere('model', 'like', "%{$term}%")
                       ->orWhere('owner_name', 'like', "%{$term}%")
                       ->orWhereHas('customer', function ($cq) use ($term) {
                           $cq->where('name', 'like', "%{$term}%")
                              ->orWhere('phone', 'like', "%{$term}%")
                              ->orWhere('nit', 'like', "%{$term}%");
                       });
                })->orWhere('id', 'like', "%{$term}%");
            });
        }

        // Filtro por Estado Operativo
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Filtro por Estado de Pago
        if ($this->paymentStatusFilter !== 'all') {
            $query->where('payment_status', $this->paymentStatusFilter);
        }

        // Filtro por Método de Pago
        if ($this->paymentMethodFilter !== 'all') {
            $query->where('payment_method', $this->paymentMethodFilter);
        }

        // Filtro por Área
        if ($this->areaFilter !== 'all') {
            $query->where('current_area_id', $this->areaFilter);
        }

        // Ajustes específicos por tipo de reporte
        if ($this->reportType === 'pagos') {
            $query->where(function ($q) {
                $q->where('paid_amount', '>', 0)
                  ->orWhere('payment_status', 'pagado')
                  ->orWhere('payment_status', 'parcial');
            });
        } elseif ($this->reportType === 'cotizaciones') {
            $query->where(function ($q) {
                $q->whereNotNull('total_amount')
                  ->orWhere('status', 'presupuesto');
            });
        }

        return $query;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $baseQuery = $this->getBaseQuery();

        // Totales y Estadísticas del conjunto filtrado
        $statsOrders = (clone $baseQuery)->get();

        $totalCount = $statsOrders->count();
        $totalQuoted = (float) $statsOrders->sum('total_amount');
        $totalPaid = (float) $statsOrders->sum('paid_amount');
        $totalPending = max(0, (float) $statsOrders->sum(fn($o) => $o->pendingPaymentAmount()));

        // Desglose por método de pago
        $paymentsByMethod = $statsOrders->where('paid_amount', '>', 0)
            ->groupBy(fn($o) => $o->payment_method ?: 'otro')
            ->map(fn($group) => $group->sum('paid_amount'));

        // Desglose por estado
        $ordersByStatus = $statsOrders->groupBy('status')
            ->map(fn($group) => $group->count());

        $metrics = [
            'ordersCount' => $totalCount,
            'totalQuoted' => $totalQuoted,
            'totalPaid' => $totalPaid,
            'totalPending' => $totalPending,
            'paymentsByMethod' => $paymentsByMethod,
            'ordersByStatus' => $ordersByStatus,
        ];

        // Paginación de resultados de la tabla
        $workOrders = (clone $baseQuery)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $areas = Area::orderBy('order')->get();

        return view('livewire.report-center', [
            'workOrders' => $workOrders,
            'areas' => $areas,
            'metrics' => $metrics,
        ]);
    }
}
