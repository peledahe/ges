<?php

namespace Tests\Feature\Livewire;

use App\Livewire\PublicTracking;
use App\Models\Area;
use App\Models\Customer;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class PublicTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected Area $area;
    protected Customer $customer;
    protected Vehicle $vehicle;
    protected WorkOrder $workOrder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Taller Los Amigos',
            'slug' => 'taller-los-amigos',
            'admin_name' => 'Mario López',
            'email' => 'mario@talleramigos.com',
            'phone' => '77889900',
            'address' => 'Zona 4, Ciudad',
            'is_active' => true,
        ]);

        $this->area = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Mecánica Mayor',
            'default_status' => 'trabajando',
        ]);

        $this->customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Alejandro Cliente',
            'email' => 'cliente@gmail.com',
            'phone' => '44556677',
        ]);

        $this->vehicle = Vehicle::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'plate' => 'P987ABC',
            'owner_name' => 'Alejandro Cliente',
            'owner_email' => 'cliente@gmail.com',
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => 2020,
            'color' => 'Gris',
            'fuel_type' => 'gasolina',
        ]);

        $this->workOrder = WorkOrder::create([
            'tenant_id' => $this->tenant->id,
            'vehicle_id' => $this->vehicle->id,
            'current_area_id' => $this->area->id,
            'status' => 'trabajando',
            'payment_type' => 'particular',
            'total_amount' => 1800.00,
            'paid_amount' => 500.00,
            'payment_status' => 'parcial',
            'notes' => 'Alineación, balanceo y cambio de pastillas de freno delanteras.',
        ]);
    }

    public function test_renders_successfully(): void
    {
        Livewire::test(PublicTracking::class)
            ->assertStatus(200)
            ->assertSee('Rastreo y Estatus de Vehículo');
    }

    public function test_can_search_vehicle_by_plate_without_login_and_see_details(): void
    {
        Livewire::test(PublicTracking::class)
            ->set('plate', 'p987abc') // minúsculas para validar normalización
            ->call('search')
            ->assertStatus(200)
            ->assertSee('P987ABC')
            ->assertSee('Honda Civic')
            ->assertSee('Mecánica Mayor')
            ->assertSee('1,800.00')
            ->assertSee('500.00')
            ->assertSee('1,300.00') // Saldo pendiente
            ->assertSee('Pago Parcial')
            ->assertSee('Alineación, balanceo');
    }

    public function test_can_search_vehicle_by_plate_scoped_by_tenant_slug(): void
    {
        // 1. Visitar ruta con slug de taller válido
        $response = $this->get(route('rastreo.tenant', $this->tenant->slug));
        $response->assertStatus(200);
        $response->assertSee($this->tenant->name);

        // 2. Buscar dentro de ese taller
        Livewire::test(PublicTracking::class, ['tenant' => $this->tenant])
            ->set('plate', 'P-987ABC')
            ->call('search')
            ->assertStatus(200)
            ->assertSee('P987ABC')
            ->assertSee('Honda Civic')
            ->assertSee($this->tenant->name);

        // 3. Crear otro taller con otro vehículo
        $tenant2 = Tenant::create([
            'name' => 'Segundo Taller AutoExpert',
            'slug' => 'auto-expert',
            'admin_name' => 'Pedro Sol',
            'email' => 'pedro@autoexpert.com',
            'phone' => '88991122',
            'is_active' => true,
        ]);

        $vehicle2 = Vehicle::create([
            'tenant_id' => $tenant2->id,
            'plate' => 'P111ZZZ',
            'owner_name' => 'Cliente Otro Taller',
            'brand' => 'Toyota',
            'model' => 'Corolla',
        ]);

        WorkOrder::create([
            'tenant_id' => $tenant2->id,
            'vehicle_id' => $vehicle2->id,
            'status' => 'recibido',
            'payment_type' => 'particular',
            'notes' => 'Mantenimiento en segundo taller',
        ]);

        // 4. Si busco la placa de tenant2 en el portal de tenant 1, NO debe encontrarlo
        Livewire::test(PublicTracking::class, ['tenant' => $this->tenant])
            ->set('plate', 'P111ZZZ')
            ->call('search')
            ->assertStatus(200)
            ->assertSee('Vehículo no encontrado')
            ->assertDontSee('Toyota Corolla');

        // 5. Si busco en el portal de tenant 2, SI lo encuentra
        Livewire::test(PublicTracking::class, ['tenant' => $tenant2])
            ->set('plate', 'P111ZZZ')
            ->call('search')
            ->assertStatus(200)
            ->assertSee('P111ZZZ')
            ->assertSee('Toyota')
            ->assertSee('Segundo Taller AutoExpert');
    }

    public function test_client_dashboard_displays_quotations_and_payments(): void
    {
        $clientUser = User::create([
            'name' => 'Alejandro Cliente',
            'email' => 'cliente@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->actingAs($clientUser)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Portal del Cliente');
        $response->assertSee('Hola, Alejandro Cliente');
        $response->assertSee('P987ABC');
        $response->assertSee('Honda Civic');
        $response->assertSee('1,800.00'); // Total cotizado
        $response->assertSee('500.00'); // Total abonado
        $response->assertSee('1,300.00'); // Saldo pendiente
        $response->assertSee('Mecánica Mayor');
    }
}
