<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->isRoot()) {
            return redirect()->route('admin.tenants');
        }

        $role = $user->role;

        $data = match($role) {
            'admin' => $this->getAdminData(),
            'manager', 'encargado_area' => $this->getAreaManagerData($user),
            'recepcion' => $this->getReceptionData(),
            'presupuestos' => $this->getBudgetData(),
            'client' => $this->getClientData($user->id),
            default => $this->getDefaultData(),
        };

        return view('dashboard', compact('data', 'role'));
    }

    private function getAdminData()
    {
        $activeOrders = WorkOrder::whereNotIn('status', ['terminado', 'entregado'])->count();
        
        $ordersByStatus = WorkOrder::whereNotIn('status', ['entregado'])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $ordersCompletedToday = WorkOrder::where('status', 'terminado')
            ->whereDate('updated_at', today())
            ->count();

        $ordersCompletedThisWeek = WorkOrder::where('status', 'terminado')
            ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $readyForDelivery = WorkOrder::where('status', 'terminado')->count();

        $usersByRole = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        $ordersByArea = Area::withCount(['workOrders' => function($query) {
            $query->whereNotIn('status', ['entregado']);
        }])->orderBy('order')->get();

        // Finanzas operativas del taller
        $totalCollectedMonth = (float) WorkOrder::whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('paid_amount');

        $activeWorkOrders = WorkOrder::whereNotIn('status', ['entregado'])->get();
        $totalActiveQuotations = (float) $activeWorkOrders->sum('total_amount');
        $totalPendingBalance = (float) $activeWorkOrders->sum(fn($o) => $o->pendingPaymentAmount());

        // Cuellos de botella: autos con más de 3 días en taller que no han finalizado
        $bottleneckOrders = WorkOrder::whereNotIn('status', ['terminado', 'entregado'])
            ->where('created_at', '<=', now()->subDays(3))
            ->with(['vehicle.customer', 'area'])
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get();

        // Órdenes recientes en proceso
        $recentOrders = WorkOrder::with(['vehicle.customer', 'area'])
            ->whereNotIn('status', ['entregado'])
            ->orderBy('updated_at', 'desc')
            ->limit(7)
            ->get();

        // Vehículos listos para entrega inmediata
        $readyOrdersList = WorkOrder::where('status', 'terminado')
            ->with(['vehicle.customer', 'area'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'active_orders' => $activeOrders,
            'orders_by_status' => $ordersByStatus,
            'orders_completed_today' => $ordersCompletedToday,
            'orders_completed_week' => $ordersCompletedThisWeek,
            'ready_for_delivery' => $readyForDelivery,
            'users_by_role' => $usersByRole,
            'orders_by_area' => $ordersByArea,
            'total_collected_month' => $totalCollectedMonth,
            'total_active_quotations' => $totalActiveQuotations,
            'total_pending_balance' => $totalPendingBalance,
            'bottleneck_orders' => $bottleneckOrders,
            'recent_orders' => $recentOrders,
            'ready_orders_list' => $readyOrdersList,
        ];
    }

    private function getAreaManagerData(User $user)
    {
        $areaIds = $user->accessibleAreaIds();

        $areasQuery = Area::query();
        if ($areaIds !== null) {
            $areasQuery->whereIn('id', $areaIds);
        }
        $assignedAreas = $areasQuery->orderBy('order')->get();

        if ($assignedAreas->isEmpty()) {
            return [
                'has_areas' => false,
                'assigned_areas' => collect(),
                'orders_in_area' => 0,
                'orders_by_status' => [],
                'active_orders' => collect(),
                'orders_working' => 0,
                'orders_review' => 0,
                'orders_waiting' => 0,
            ];
        }

        $assignedAreaIds = $assignedAreas->pluck('id')->toArray();

        $ordersInArea = WorkOrder::whereIn('current_area_id', $assignedAreaIds)
            ->whereNotIn('status', ['entregado'])
            ->count();

        $ordersByStatus = WorkOrder::whereIn('current_area_id', $assignedAreaIds)
            ->whereNotIn('status', ['entregado'])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $ordersWorking = WorkOrder::whereIn('current_area_id', $assignedAreaIds)
            ->where('status', 'trabajando')
            ->count();

        $ordersReview = WorkOrder::whereIn('current_area_id', $assignedAreaIds)
            ->where('status', 'revision')
            ->count();

        $ordersWaiting = WorkOrder::whereIn('current_area_id', $assignedAreaIds)
            ->where('status', 'en_espera')
            ->count();

        $activeOrders = WorkOrder::whereIn('current_area_id', $assignedAreaIds)
            ->whereNotIn('status', ['entregado'])
            ->with(['vehicle.customer', 'area', 'checklists'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return [
            'has_areas' => true,
            'assigned_areas' => $assignedAreas,
            'orders_in_area' => $ordersInArea,
            'orders_by_status' => $ordersByStatus,
            'orders_working' => $ordersWorking,
            'orders_review' => $ordersReview,
            'orders_waiting' => $ordersWaiting,
            'active_orders' => $activeOrders,
            'can_view_contact_info' => (bool) $user->can_view_contact_info,
        ];
    }

    private function getReceptionData()
    {
        $ordersToday = WorkOrder::whereDate('created_at', today())->count();
        
        $ordersThisWeek = WorkOrder::whereBetween('created_at', [
            now()->startOfWeek(), 
            now()->endOfWeek()
        ])->count();

        $pendingBudget = WorkOrder::where('status', 'presupuesto')->count();

        $readyForDeliveryCount = WorkOrder::where('status', 'terminado')->count();

        // Vehículos listos para entrega con saldos
        $readyOrders = WorkOrder::where('status', 'terminado')
            ->with(['vehicle.customer', 'area'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $readyWithPendingPayment = $readyOrders->filter(fn($o) => $o->pendingPaymentAmount() > 0)->count();

        $recentOrders = WorkOrder::with(['vehicle.customer', 'area'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'orders_today' => $ordersToday,
            'orders_week' => $ordersThisWeek,
            'pending_budget' => $pendingBudget,
            'ready_for_delivery_count' => $readyForDeliveryCount,
            'ready_with_pending_payment' => $readyWithPendingPayment,
            'ready_orders' => $readyOrders,
            'recent_orders' => $recentOrders,
        ];
    }

    private function getBudgetData()
    {
        $pendingBudget = WorkOrder::where('status', 'presupuesto')->count();
        
        $budgetsApprovedToday = WorkOrder::where('status', 'trabajando')
            ->whereDate('updated_at', today())
            ->count();

        $budgetsApprovedWeek = WorkOrder::where('status', 'trabajando')
            ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $totalBudgetedMonth = (float) WorkOrder::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereNotNull('total_amount')
            ->sum('total_amount');

        // Órdenes en estado presupuesto o en áreas con permiso de generar cotización
        $ordersNeedingBudget = WorkOrder::where(function($query) {
                $query->where('status', 'presupuesto')
                    ->orWhereHas('area', function($q) {
                        $q->where('can_generate_quotation', true);
                    });
            })
            ->whereNotIn('status', ['terminado', 'entregado'])
            ->with(['vehicle.customer', 'area'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Cotizaciones recientes aprobadas o en curso
        $recentQuotedOrders = WorkOrder::whereNotNull('total_amount')
            ->where('total_amount', '>', 0)
            ->whereNotIn('status', ['recibido', 'presupuesto'])
            ->with(['vehicle.customer', 'area'])
            ->orderBy('updated_at', 'desc')
            ->limit(6)
            ->get();

        return [
            'pending_budget' => $pendingBudget,
            'budgets_approved_today' => $budgetsApprovedToday,
            'budgets_approved_week' => $budgetsApprovedWeek,
            'total_budgeted_month' => $totalBudgetedMonth,
            'orders_needing_budget' => $ordersNeedingBudget,
            'recent_quoted_orders' => $recentQuotedOrders,
        ];
    }

    private function getClientData($userId)
    {
        $user = User::with('tenant')->find($userId);
        if (!$user) {
            return $this->getDefaultData();
        }

        $tenantId = $user->tenant_id;

        // 1. Clientes relacionados por correo electrónico o nombre
        $customersQuery = Customer::withoutGlobalScopes();
        if ($tenantId) {
            $customersQuery->where('tenant_id', $tenantId);
        }
        $customerIds = $customersQuery->where(function ($q) use ($user) {
            $q->where('email', $user->email);
            if (!empty($user->name)) {
                $q->orWhere('name', $user->name);
            }
        })->pluck('id');

        // 2. Vehículos vinculados por customer_id, correo de propietario o nombre
        $vehiclesQuery = Vehicle::withoutGlobalScopes();
        if ($tenantId) {
            $vehiclesQuery->where('tenant_id', $tenantId);
        }
        $vehicles = $vehiclesQuery->where(function ($q) use ($user, $customerIds) {
            if ($customerIds->isNotEmpty()) {
                $q->whereIn('customer_id', $customerIds);
            }
            $q->orWhere('owner_email', $user->email);
            if (!empty($user->name)) {
                $q->orWhere('owner_name', $user->name);
            }
        })
        ->with(['customer'])
        ->get();

        $vehicleIds = $vehicles->pluck('id');

        // 3. Órdenes de trabajo correspondientes a los vehículos del cliente
        $ordersQuery = WorkOrder::withoutGlobalScopes();
        if ($tenantId) {
            $ordersQuery->where('tenant_id', $tenantId);
        }
        $orders = $ordersQuery->whereIn('vehicle_id', $vehicleIds)
            ->with(['vehicle', 'area', 'checklists'])
            ->orderBy('created_at', 'desc')
            ->get();

        $activeOrders = $orders->where('status', '!=', 'entregado');
        $historyOrders = $orders->where('status', '===', 'entregado');

        // 4. Métricas Financieras
        $totalQuotations = (float) $orders->sum('total_amount');
        $totalPaid = (float) $orders->sum('paid_amount');
        $pendingPayment = max(0, $totalQuotations - $totalPaid);

        return [
            'user' => $user,
            'tenant' => $user->tenant,
            'vehicles' => $vehicles,
            'orders' => $orders,
            'active_orders' => $activeOrders,
            'history_orders' => $historyOrders,
            'total_quotations' => $totalQuotations,
            'total_paid' => $totalPaid,
            'pending_payment' => $pendingPayment,
            'active_orders_count' => $activeOrders->count(),
            'completed_orders_count' => $historyOrders->count(),
        ];
    }

    private function getDefaultData()
    {
        return [
            'message' => 'Bienvenido al sistema de gestión de taller',
        ];
    }
}
