<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ReceptionForm;
use App\Livewire\UserManagement;
use App\Models\Area;
use App\Models\Customer;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClientUserAndDashboardProfilesTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private Area $areaRecep;
    private Area $areaTaller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Taller Preferencial Test',
            'slug' => 'taller-preferencial-test',
            'admin_name' => 'Admin Test',
            'email' => 'admin@preferencial.com',
            'phone' => '12345678',
            'is_active' => true,
        ]);

        $this->areaRecep = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Recepción',
            'order' => 1,
            'can_collect_payment' => true,
        ]);

        $this->areaTaller = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Mecánica General',
            'order' => 2,
        ]);
        session(['tenant_id' => $this->tenant->id]);
    }

    public function test_reception_defaults_to_inactivo_for_client_user(): void
    {
        $receptionist = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'recepcion',
            'is_active' => true,
        ]);

        $this->actingAs($receptionist);

        Livewire::test(ReceptionForm::class)
            ->assertSet('client_user_status', 'inactivo')
            ->call('toggleClientUserStatus')
            ->assertSet('client_user_status', 'activo')
            ->call('toggleClientUserStatus')
            ->assertSet('client_user_status', 'inactivo');
    }

    public function test_reception_creates_active_client_user_when_selector_is_active(): void
    {
        $receptionist = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'recepcion',
            'is_active' => true,
        ]);

        $this->actingAs($receptionist);

        Livewire::test(ReceptionForm::class)
            ->set('owner_name', 'Carlos Preferencial')
            ->set('owner_phone', '50255551234')
            ->set('owner_email', 'carlos.preferencial@test.com')
            ->set('plate', 'PREF123')
            ->set('type', 'sedan')
            ->set('brand', 'Toyota')
            ->set('model', 'Corolla')
            ->set('year', 2022)
            ->set('doors_qty', 4)
            ->set('mileage', 25000)
            ->set('fuel_level', '1/2')
            ->set('client_user_status', 'activo')
            ->call('nextStep') // Step 1 -> 2
            ->call('nextStep') // Step 2 -> 3
            ->call('nextStep') // Step 3 -> 4
            ->call('submit');

        $user = User::where('email', 'carlos.preferencial@test.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('client', $user->role);
        $this->assertTrue($user->isActive());
        $this->assertEquals($this->tenant->id, $user->tenant_id);
    }

    public function test_reception_does_not_create_client_user_when_inactivo(): void
    {
        $receptionist = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'recepcion',
            'is_active' => true,
        ]);

        $this->actingAs($receptionist);

        Livewire::test(ReceptionForm::class)
            ->set('owner_name', 'Pedro Normal')
            ->set('owner_phone', '50255559999')
            ->set('owner_email', 'pedro.normal@test.com')
            ->set('plate', 'NORM123')
            ->set('type', 'sedan')
            ->set('brand', 'Honda')
            ->set('model', 'Civic')
            ->set('year', 2020)
            ->set('doors_qty', 4)
            ->set('mileage', 40000)
            ->set('fuel_level', '1/2')
            ->set('client_user_status', 'inactivo')
            ->call('nextStep')
            ->call('nextStep')
            ->call('nextStep')
            ->call('submit');

        $user = User::where('email', 'pedro.normal@test.com')->first();
        $this->assertNull($user);
    }

    public function test_user_management_defaults_to_inactivo_when_selecting_client_role(): void
    {
        $admin = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(UserManagement::class)
            ->call('create')
            ->set('role', 'client')
            ->assertSet('is_active', false);
    }

    public function test_inactive_user_cannot_browse_and_is_logged_out(): void
    {
        $inactiveUser = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'client',
            'is_active' => false,
        ]);

        $response = $this->actingAs($inactiveUser)->get('/dashboard');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_admin_dashboard_renders_with_enriched_metrics(): void
    {
        $admin = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Control Operativo y Financiero');
        $response->assertSee('Ingresos del Mes');
        $response->assertSee('Cotizado Activo');
    }

    public function test_area_manager_dashboard_renders_with_multi_area_support(): void
    {
        $manager = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'manager',
            'is_active' => true,
        ]);
        $manager->areas()->sync([$this->areaTaller->id]);

        $response = $this->actingAs($manager)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Panel de Taller');
        $response->assertSee('Mecánica General');
        $response->assertSee('En Reparación Activa');
    }

    public function test_reception_dashboard_renders_with_delivery_focus(): void
    {
        $recepcion = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'recepcion',
            'is_active' => true,
        ]);

        $response = $this->actingAs($recepcion)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Módulo de Atención y Salidas');
        $response->assertSee('Listos para Entrega');
    }

    public function test_budget_dashboard_renders_with_budget_queue(): void
    {
        $presupuestos = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'presupuestos',
            'is_active' => true,
        ]);

        $response = $this->actingAs($presupuestos)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Panel de Presupuestos y Cotizaciones');
        $response->assertSee('Cola de Vehículos en Diagnóstico / Presupuesto');
    }
}
