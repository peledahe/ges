<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ReportCenter;
use App\Models\Area;
use App\Models\Customer;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportCenterTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $adminUser;
    protected Area $receptionArea;
    protected Area $mechanicArea;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Taller Reportes S.A.',
            'slug' => 'taller-reportes',
            'admin_name' => 'Admin Demo',
            'email' => 'admin@taller.com',
            'phone' => '12345678',
            'address' => 'Ciudad de Guatemala',
            'users_limit' => 5,
            'is_active' => true,
            'exchange_rate' => 7.80,
            'default_currency' => 'GTQ',
        ]);

        $this->adminUser = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'admin',
        ]);

        $this->receptionArea = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Recepción',
            'default_status' => 'recibido',
            'order' => 1,
        ]);

        $this->mechanicArea = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Mecánica',
            'default_status' => 'trabajando',
            'order' => 2,
        ]);
    }

    public function test_report_center_page_is_accessible(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('reportes.index'));
        $response->assertStatus(200);
        $response->assertSee('Centro de Análisis y Reportes');
    }

    public function test_report_center_filters_by_period_and_calculates_metrics(): void
    {
        $vehicle = Vehicle::create([
            'tenant_id' => $this->tenant->id,
            'plate' => 'P-999REP',
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => 2021,
            'color' => 'Rojo',
            'owner_name' => 'Juan Pérez',
        ]);

        // Orden de hoy
        WorkOrder::create([
            'tenant_id' => $this->tenant->id,
            'vehicle_id' => $vehicle->id,
            'current_area_id' => $this->receptionArea->id,
            'status' => 'trabajando',
            'payment_type' => 'particular',
            'total_amount' => 1500.00,
            'paid_amount' => 500.00,
            'payment_status' => 'parcial',
            'payment_method' => 'efectivo',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Orden de mes anterior
        WorkOrder::create([
            'tenant_id' => $this->tenant->id,
            'vehicle_id' => $vehicle->id,
            'current_area_id' => $this->mechanicArea->id,
            'status' => 'terminado',
            'payment_type' => 'particular',
            'total_amount' => 3000.00,
            'paid_amount' => 3000.00,
            'payment_status' => 'pagado',
            'payment_method' => 'tarjeta',
            'created_at' => Carbon::now()->subMonths(2),
            'updated_at' => Carbon::now()->subMonths(2),
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(ReportCenter::class)
            ->set('period', 'month')
            ->assertViewHas('metrics', function ($metrics) {
                return $metrics['totalQuoted'] == 1500.00
                    && $metrics['totalPaid'] == 500.00
                    && $metrics['totalPending'] == 1000.00
                    && $metrics['ordersCount'] == 1;
            })
            ->assertSee('P-999REP');
    }

    public function test_report_center_filter_by_report_type_and_payment_status(): void
    {
        $vehicle = Vehicle::create([
            'tenant_id' => $this->tenant->id,
            'plate' => 'P-PAID01',
            'brand' => 'Mazda',
            'model' => '3',
            'year' => 2019,
            'color' => 'Azul',
            'owner_name' => 'Cliente Pagador',
        ]);

        // Orden pagada
        WorkOrder::create([
            'tenant_id' => $this->tenant->id,
            'vehicle_id' => $vehicle->id,
            'current_area_id' => $this->mechanicArea->id,
            'status' => 'terminado',
            'payment_type' => 'particular',
            'total_amount' => 800.00,
            'paid_amount' => 800.00,
            'payment_status' => 'pagado',
            'payment_method' => 'transferencia',
            'created_at' => Carbon::now(),
        ]);

        // Orden pendiente
        WorkOrder::create([
            'tenant_id' => $this->tenant->id,
            'vehicle_id' => $vehicle->id,
            'current_area_id' => $this->receptionArea->id,
            'status' => 'presupuesto',
            'payment_type' => 'particular',
            'total_amount' => 2500.00,
            'paid_amount' => 0.00,
            'payment_status' => 'pendiente',
            'payment_method' => 'efectivo',
            'created_at' => Carbon::now(),
        ]);

        // Reporte Pagos filtrando sólo pagados
        Livewire::actingAs($this->adminUser)
            ->test(ReportCenter::class)
            ->set('reportType', 'pagos')
            ->set('period', 'month')
            ->set('paymentStatusFilter', 'pagado')
            ->assertViewHas('workOrders', function ($workOrders) {
                return $workOrders->total() === 1
                    && $workOrders->first()->payment_status === 'pagado';
            });
    }

    public function test_report_center_supports_pagination(): void
    {
        $vehicle = Vehicle::create([
            'tenant_id' => $this->tenant->id,
            'plate' => 'P-PAGINATE',
            'brand' => 'Toyota',
            'model' => 'Yaris',
            'year' => 2022,
            'color' => 'Blanco',
            'owner_name' => 'Dueño Paginación',
        ]);

        // Crear 15 órdenes para verificar paginación (límite por página es 12)
        for ($i = 1; $i <= 15; $i++) {
            WorkOrder::create([
                'tenant_id' => $this->tenant->id,
                'vehicle_id' => $vehicle->id,
                'current_area_id' => $this->receptionArea->id,
                'status' => 'recibido',
                'payment_type' => 'particular',
                'total_amount' => 100 * $i,
                'paid_amount' => 50 * $i,
                'payment_status' => 'parcial',
                'payment_method' => 'efectivo',
                'created_at' => Carbon::now(),
            ]);
        }

        Livewire::actingAs($this->adminUser)
            ->test(ReportCenter::class)
            ->set('period', 'month')
            ->assertViewHas('workOrders', function ($workOrders) {
                return $workOrders->total() === 15 
                    && $workOrders->perPage() === 12
                    && $workOrders->count() === 12;
            })
            ->call('gotoPage', 2)
            ->assertViewHas('workOrders', function ($workOrders) {
                return $workOrders->currentPage() === 2
                    && $workOrders->count() === 3;
            });
    }
}
