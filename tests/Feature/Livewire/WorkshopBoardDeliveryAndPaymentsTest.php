<?php

namespace Tests\Feature\Livewire;

use App\Livewire\WorkshopBoard;
use App\Models\Area;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class WorkshopBoardDeliveryAndPaymentsTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private User $adminUser;
    private Area $receptionArea;
    private Area $mechanicArea;
    private Area $deliveryArea;
    private Vehicle $vehicle;
    private WorkOrder $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Taller AutoPro',
            'slug' => 'autopro',
            'admin_name' => 'Pedro Taller',
            'email' => 'admin@autopro.com',
        ]);

        $this->adminUser = User::create([
            'name' => 'Pedro Admin',
            'email' => 'admin@autopro.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'tenant_id' => $this->tenant->id,
        ]);

        session(['tenant_id' => $this->tenant->id]);

        $this->receptionArea = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Recepción',
            'default_status' => 'recibido',
            'order' => 1,
        ]);

        $this->mechanicArea = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Mecánica General',
            'default_status' => 'trabajando',
            'order' => 2,
        ]);

        $this->deliveryArea = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Entrega',
            'default_status' => 'terminado',
            'is_delivery' => true,
            'order' => 3,
        ]);

        $this->vehicle = Vehicle::create([
            'tenant_id' => $this->tenant->id,
            'plate' => 'P-123XYZ',
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'color' => 'Gris',
            'owner_name' => 'Cliente Feliz',
        ]);

        $this->order = WorkOrder::create([
            'tenant_id' => $this->tenant->id,
            'vehicle_id' => $this->vehicle->id,
            'current_area_id' => $this->receptionArea->id,
            'status' => 'recibido',
            'payment_type' => 'particular',
        ]);
    }

    public function test_card_status_changes_automatically_when_moved_to_different_area(): void
    {
        // Al mover de Recepción a Mecánica General, el estado debe cambiar automáticamente a 'trabajando'
        Livewire::actingAs($this->adminUser)
            ->test(WorkshopBoard::class)
            ->call('updateOrderArea', $this->order->id, $this->mechanicArea->id)
            ->assertDispatched('notify');

        $this->assertEquals('trabajando', $this->order->fresh()->status);
        $this->assertEquals($this->mechanicArea->id, $this->order->fresh()->current_area_id);

        // Al mover al área de Entrega, el estado debe cambiar automáticamente a 'terminado'
        Livewire::actingAs($this->adminUser)
            ->test(WorkshopBoard::class)
            ->call('updateOrderArea', $this->order->id, $this->deliveryArea->id)
            ->assertDispatched('notify');

        $this->assertEquals('terminado', $this->order->fresh()->status);
        $this->assertEquals($this->deliveryArea->id, $this->order->fresh()->current_area_id);
    }

    public function test_can_register_payment_for_work_order(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(WorkshopBoard::class)
            ->call('openPaymentModal', $this->order->id)
            ->set('paymentTotalAmount', '1500.00')
            ->set('paymentPaidAmount', '1500.00')
            ->set('paymentMethod', 'tarjeta')
            ->set('paymentReference', 'POS-8921')
            ->call('savePayment')
            ->assertDispatched('notify');

        $updated = $this->order->fresh();
        $this->assertEquals('1500.00', $updated->total_amount);
        $this->assertEquals('1500.00', $updated->paid_amount);
        $this->assertEquals('pagado', $updated->payment_status);
        $this->assertEquals('tarjeta', $updated->payment_method);
        $this->assertEquals('POS-8921', $updated->payment_reference);
        $this->assertNotNull($updated->paid_at);
    }

    public function test_delivering_vehicle_sets_status_entregado_and_removes_from_kanban(): void
    {
        // Verificamos que inicialmente está en el tablero
        $component = Livewire::actingAs($this->adminUser)->test(WorkshopBoard::class);
        $areas = $component->get('areas');
        $this->assertTrue($areas->firstWhere('id', $this->receptionArea->id)->workOrders->contains('id', $this->order->id));

        // Entregamos el vehículo
        $component->call('openDeliveryModal', $this->order->id)
            ->call('confirmDelivery')
            ->assertDispatched('notify');

        $updated = $this->order->fresh();
        $this->assertEquals('entregado', $updated->status);
        $this->assertNotNull($updated->delivered_at);

        // Al refrescar el tablero, la orden entregada ya no debe aparecer en ninguna columna del Kanban
        $component->call('refreshBoard');
        $updatedAreas = $component->get('areas');
        foreach ($updatedAreas as $a) {
            $this->assertFalse($a->workOrders->contains('id', $this->order->id));
        }
    }

    public function test_can_open_delivery_modal_with_correct_vehicle_details(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(WorkshopBoard::class)
            ->assertSet('showDeliveryModal', false)
            ->call('openDeliveryModal', $this->order->id)
            ->assertSet('showDeliveryModal', true)
            ->assertSet('deliveryOrderId', $this->order->id)
            ->assertSeeHtml('Confirmar Entrega de Vehículo')
            ->assertSeeHtml($this->vehicle->plate)
            ->assertSeeHtml($this->vehicle->brand)
            ->assertSeeHtml($this->vehicle->owner_name);
    }

    public function test_user_only_sees_assigned_areas_on_kanban(): void
    {
        // Mecánico asignado únicamente al área de Mecánica General
        $mechanic = User::create([
            'name' => 'Mecánico Juan',
            'email' => 'juan@autopro.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'tenant_id' => $this->tenant->id,
        ]);
        $mechanic->areas()->sync([$this->mechanicArea->id]);

        $component = Livewire::actingAs($mechanic)->test(WorkshopBoard::class);
        $visibleAreas = $component->get('areas');

        $this->assertCount(1, $visibleAreas);
        $this->assertEquals($this->mechanicArea->id, $visibleAreas->first()->id);
        $this->assertFalse($visibleAreas->contains('id', $this->receptionArea->id));
        $this->assertFalse($visibleAreas->contains('id', $this->deliveryArea->id));
    }

    public function test_single_person_or_admin_sees_all_areas(): void
    {
        // Admin ve todas las áreas
        $component = Livewire::actingAs($this->adminUser)->test(WorkshopBoard::class);
        $visibleAreas = $component->get('areas');
        $this->assertCount(3, $visibleAreas);
    }

    public function test_area_quotation_and_collect_permissions_workflow(): void
    {
        // Configuramos la mecánica general: sin permiso de cotizar, sin ver monto y sin cobrar
        $this->mechanicArea->update([
            'can_generate_quotation' => false,
            'can_view_quotation_amount' => false,
            'can_collect_payment' => false,
        ]);

        // Área de recepción: sí cotiza, sí ve montos y sí cobra
        $this->receptionArea->update([
            'can_generate_quotation' => true,
            'can_view_quotation_amount' => true,
            'can_collect_payment' => true,
        ]);

        $mechanic = User::create([
            'name' => 'Técnico Pedro',
            'email' => 'pedro@autopro.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'tenant_id' => $this->tenant->id,
        ]);
        $mechanic->areas()->sync([$this->mechanicArea->id]);

        // Movemos la orden a mecánica
        $this->order->update(['current_area_id' => $this->mechanicArea->id]);

        // Mecánico intenta guardar cotización en área no autorizada
        Livewire::actingAs($mechanic)
            ->test(WorkshopBoard::class)
            ->call('selectOrder', $this->order->id)
            ->set('quotationTotalAmount', '3500.00')
            ->call('saveQuotation')
            ->assertDispatched('notify');

        // El monto no debe haberse guardado como 3500
        $this->assertNotEquals('3500.00', (string) $this->order->fresh()->total_amount);

        // Ahora el Admin en recepción o presupuesto guarda la cotización
        $this->order->update(['current_area_id' => $this->receptionArea->id]);

        Livewire::actingAs($this->adminUser)
            ->test(WorkshopBoard::class)
            ->call('selectOrder', $this->order->id)
            ->set('quotationTotalAmount', '2800.00')
            ->set('quotationNotes', 'Cambio de amortiguadores delanteros')
            ->call('saveQuotation')
            ->assertDispatched('notify');

        $this->assertEquals('2800.00', (string) $this->order->fresh()->total_amount);
        $this->assertEquals('Cambio de amortiguadores delanteros', $this->order->fresh()->notes);
    }
}
