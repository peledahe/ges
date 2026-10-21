<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Area;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\ChecklistTemplate;
use App\Livewire\ReceptionForm;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class ReceptionCustomerWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Taller Test',
            'slug' => 'taller-test',
            'admin_name' => 'Admin Test',
            'email' => 'admin@test.com',
            'phone' => '12345678',
        ]);

        $this->user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'tenant_id' => $this->tenant->id,
            'role' => 'admin',
        ]);

        Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Recepción',
        ]);

        ChecklistTemplate::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'General',
            'items' => json_encode([
                ['section' => 'Exterior', 'items' => ['Luces', 'Antena']]
            ]),
        ]);

        Session::put('tenant_id', $this->tenant->id);
    }

    public function test_can_search_and_select_customer_by_phone_or_nit(): void
    {
        $this->actingAs($this->user);

        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Carlos Mendoza',
            'phone' => '55512345',
            'email' => 'carlos@mendoza.gt',
            'nit' => '889900-1',
            'address' => 'Zona 14, Ciudad de Guatemala',
        ]);

        Vehicle::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'plate' => 'P111AAA',
            'brand' => 'Mazda',
            'model' => '3',
            'type' => 'sedan',
            'doors_qty' => 4,
            'owner_name' => $customer->name,
        ]);

        // 1. Buscar por teléfono
        Livewire::test(ReceptionForm::class)
            ->set('search_customer', '55512345')
            ->call('searchCustomer')
            ->assertSet('customer_id', $customer->id)
            ->assertSet('owner_name', 'Carlos Mendoza')
            ->assertSet('owner_email', 'carlos@mendoza.gt')
            ->assertSet('owner_nit', '889900-1')
            ->assertCount('customer_vehicles', 1);

        // 2. Buscar por NIT
        Livewire::test(ReceptionForm::class)
            ->set('search_customer', '889900-1')
            ->call('searchCustomer')
            ->assertSet('customer_id', $customer->id)
            ->assertSet('owner_name', 'Carlos Mendoza');
    }

    public function test_can_select_customer_vehicle_with_one_click(): void
    {
        $this->actingAs($this->user);

        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Mariana Ruiz',
            'phone' => '44445555',
            'email' => 'mariana@ruiz.com',
            'nit' => '776655-2',
        ]);

        $vehicle = Vehicle::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'plate' => 'P999ZZZ',
            'brand' => 'Honda',
            'model' => 'CR-V',
            'type' => 'suv',
            'doors_qty' => 5,
            'owner_name' => $customer->name,
        ]);

        Livewire::test(ReceptionForm::class)
            ->call('selectCustomer', $customer->id)
            ->assertSet('is_existing_customer', true)
            ->call('selectCustomerVehicle', $vehicle->id)
            ->assertSet('plate', 'P999ZZZ')
            ->assertSet('brand', 'Honda')
            ->assertSet('model', 'CR-V')
            ->assertSet('type', 'suv');
    }

    public function test_creates_new_customer_and_links_vehicle_on_submit(): void
    {
        $this->actingAs($this->user);

        Livewire::test(ReceptionForm::class)
            // Paso 1: Datos del cliente nuevo
            ->set('owner_name', 'Roberto Gomez')
            ->set('owner_phone', '33332222')
            ->set('owner_email', 'roberto@gomez.com')
            ->set('owner_nit', '123456-7')
            ->set('owner_address', 'Carretera a El Salvador')
            // Datos del vehículo
            ->set('plate', 'P777NEW')
            ->set('type', 'pickup')
            ->set('brand' ,'Toyota')
            ->set('model', 'Hilux')
            ->set('doors_qty', 4)
            ->set('mileage', 45000)
            ->set('fuel_level', '1/2')
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->call('nextStep')
            ->assertSet('currentStep', 3)
            ->call('nextStep')
            ->assertSet('currentStep', 4)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        // Verificar que el cliente se creó en la base de datos
        $this->assertDatabaseHas('customers', [
            'name' => 'Roberto Gomez',
            'phone' => '33332222',
            'email' => 'roberto@gomez.com',
            'nit' => '123456-7',
        ]);

        $customer = Customer::where('nit', '123456-7')->first();
        $this->assertNotNull($customer);

        // Verificar que el vehículo quedó asociado al cliente
        $this->assertDatabaseHas('vehicles', [
            'plate' => 'P777NEW',
            'customer_id' => $customer->id,
            'brand' => 'Toyota',
            'model' => 'Hilux',
        ]);

        // Verificar que el cliente tiene 1 vehículo y 1 orden de trabajo
        $this->assertEquals(1, $customer->vehicles()->count());
        $this->assertEquals(1, $customer->workOrders()->count());
    }

    public function test_can_register_electric_vehicle_with_charge_level(): void
    {
        Livewire::test(ReceptionForm::class)
            ->set('owner_name', 'Elon Perez')
            ->set('owner_phone', '99887766')
            ->set('plate', 'P-EV999')
            ->set('type', 'sedan')
            ->set('brand', 'Tesla')
            ->set('model', 'Model 3')
            ->set('doors_qty', 4)
            ->set('fuel_type', 'electrico')
            ->set('mileage', 12000)
            ->set('fuel_level', 'F') // 100% Carga
            ->call('nextStep')
            ->call('nextStep')
            ->call('nextStep')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('vehicles', [
            'plate' => 'P-EV999',
            'fuel_type' => 'electrico',
            'brand' => 'Tesla',
        ]);

        $this->assertDatabaseHas('work_orders', [
            'fuel_type' => 'electrico',
            'fuel_level' => 'F',
        ]);
    }

    public function test_can_select_moto_and_pasola_with_diagram_and_zero_doors(): void
    {
        $this->actingAs($this->user);

        // Prueba para Motocicleta
        Livewire::test(ReceptionForm::class)
            ->set('owner_name', 'Carlos Motero')
            ->set('owner_phone', '44445555')
            ->set('plate', 'M-123XYZ')
            ->set('type', 'moto')
            ->assertSet('doors_qty', 0)
            ->assertSet('vehicleImage', 'moto.png')
            ->set('brand', 'Yamaha')
            ->set('model', 'FZ 250')
            ->set('mileage', 5000)
            ->set('fuel_level', '1/2')
            ->call('nextStep')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 2)
            ->assertSee('Manecilla de Freno Delantero')
            ->assertSee('Cadena de Transmisión')
            ->assertDontSee('Tapicería de Baúl');

        // Prueba para Pasola
        Livewire::test(ReceptionForm::class)
            ->set('owner_name', 'Lucia Scooter')
            ->set('owner_phone', '55556666')
            ->set('plate', 'M-987ABC')
            ->set('type', 'pasola')
            ->assertSet('doors_qty', 0)
            ->assertSet('vehicleImage', 'pasola.png')
            ->set('brand', 'Suzuki')
            ->set('model', 'Access 125')
            ->set('mileage', 3200)
            ->set('fuel_level', '3/4')
            ->call('nextStep')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 2)
            ->assertSee('Baúl debajo del Sillín')
            ->assertSee('Carenaje Frontal');
    }

    public function test_checklist_adapts_for_pickup_and_sedan(): void
    {
        $this->actingAs($this->user);

        // Prueba para Pickup
        Livewire::test(ReceptionForm::class)
            ->set('type', 'pickup')
            ->set('currentStep', 2)
            ->assertSee('Compuerta de Palangana')
            ->assertSee('Protector de Palangana (Duraliner)');

        // Prueba para Sedán
        Livewire::test(ReceptionForm::class)
            ->set('type', 'sedan')
            ->set('currentStep', 2)
            ->assertSee('Tapicería de Baúl')
            ->assertSee('Llave de Chucho')
            ->assertSee('Tricket');
    }

    public function test_summary_displays_client_requirement_notes(): void
    {
        $this->actingAs($this->user);

        Livewire::test(ReceptionForm::class)
            ->set('brand', 'Toyota')
            ->set('model', 'Rav 4')
            ->set('year', 2015)
            ->set('plate', 'P-324GKR')
            ->set('owner_name', 'Juan Perez')
            ->set('mileage', 65240)
            ->set('fuel_type', 'gasolina')
            ->set('fuel_level', 'E')
            ->set('notes', 'Cambio de pastillas de freno y revisión de suspensión delantera.')
            ->set('currentStep', 4)
            ->assertSee('Requerimiento del Cliente')
            ->assertSee('Cambio de pastillas de freno y revisión de suspensión delantera.');
    }
}
