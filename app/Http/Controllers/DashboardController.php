<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\User;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        $data = match($role) {
            'admin' => $this->getAdminData(),
            'encargado_area' => $this->getAreaManagerData($user->area_id),
            'recepcion' => $this->getReceptionData(),
            'presupuestos' => $this->getBudgetData(),
            'client' => $this->getClientData($user->id),
            default => $this->getDefaultData(),
        };

        return view('dashboard', compact('data', 'role'));
    }

    private function getAdminData()
    {
        $activeOrders = WorkOrder::whereNotIn('status', ['terminado'])->count();
        
        $ordersByStatus = WorkOrder::whereNotIn('status', ['terminado'])
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

        $usersByRole = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        $ordersByArea = Area::withCount(['workOrders' => function($query) {
            $query->whereNotIn('status', ['terminado']);
        }])->get();

        return [
            'active_orders' => $activeOrders,
            'orders_by_status' => $ordersByStatus,
            'orders_completed_today' => $ordersCompletedToday,
            'orders_completed_week' => $ordersCompletedThisWeek,
            'users_by_role' => $usersByRole,
            'orders_by_area' => $ordersByArea,
        ];
    }

    private function getAreaManagerData($areaId)
    {
        if (!$areaId) {
            return $this->getDefaultData();
        }

        $area = Area::find($areaId);
        
        $ordersInArea = WorkOrder::where('current_area_id', $areaId)
            ->whereNotIn('status', ['terminado'])
            ->count();

        $ordersByStatus = WorkOrder::where('current_area_id', $areaId)
            ->whereNotIn('status', ['terminado'])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $activeOrders = WorkOrder::where('current_area_id', $areaId)
            ->whereNotIn('status', ['terminado'])
            ->with(['vehicle'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return [
            'area' => $area,
            'orders_in_area' => $ordersInArea,
            'orders_by_status' => $ordersByStatus,
            'active_orders' => $activeOrders,
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

        $recentOrders = WorkOrder::with(['vehicle', 'area'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'orders_today' => $ordersToday,
            'orders_week' => $ordersThisWeek,
            'pending_budget' => $pendingBudget,
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

        $ordersNeedingBudget = WorkOrder::where('status', 'presupuesto')
            ->with(['vehicle', 'area'])
            ->orderBy('created_at', 'asc')
            ->get();

        return [
            'pending_budget' => $pendingBudget,
            'budgets_approved_today' => $budgetsApprovedToday,
            'budgets_approved_week' => $budgetsApprovedWeek,
            'orders_needing_budget' => $ordersNeedingBudget,
        ];
    }

    private function getClientData($userId)
    {
        // For now, return empty data as clients don't have vehicles directly linked
        // This would need to be implemented based on your client-vehicle relationship
        return [
            'my_orders' => collect([]),
            'message' => 'Funcionalidad de cliente en desarrollo',
        ];
    }

    private function getDefaultData()
    {
        return [
            'message' => 'Bienvenido al sistema de gestión de taller',
        ];
    }
}
