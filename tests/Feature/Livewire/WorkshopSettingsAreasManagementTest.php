<?php

namespace Tests\Feature\Livewire;

use App\Livewire\WorkshopSettings;
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

class WorkshopSettingsAreasManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $adminUser;
    protected User $mechanicUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Taller Rápido GT',
            'slug' => 'taller-rapido-gt',
            'admin_name' => 'Carlos Gerente',
            'email' => 'gerencia@tallergt.com',
            'phone' => '12345678',
            'address' => 'Zona 10, Guatemala',
            'is_active' => true,
        ]);

        $this->adminUser = User::create([
            'name' => 'Carlos Gerente',
            'email' => 'gerencia@tallergt.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'tenant_id' => $this->tenant->id,
        ]);

        $this->mechanicUser = User::create([
            'name' => 'Luis Mecánico',
            'email' => 'luis@tallergt.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_workshop_settings_renders_areas_and_users(): void
    {
        $area = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Área de Pintura',
            'default_status' => 'trabajando',
            'can_generate_quotation' => false,
            'can_view_quotation_amount' => true,
            'can_collect_payment' => false,
            'is_delivery' => false,
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(WorkshopSettings::class)
            ->assertStatus(200)
            ->assertSee('Área de Pintura')
            ->assertSee('Flujo de Trabajo y Áreas Operativas');
    }

    public function test_can_create_new_area_with_assigned_users_and_permissions(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(WorkshopSettings::class)
            ->call('openCreateAreaModal')
            ->set('areaName', 'Diagnóstico y Presupuestos')
            ->set('areaDefaultStatus', 'presupuesto')
            ->set('areaCanGenerateQuotation', true)
            ->set('areaCanViewQuotationAmount', true)
            ->set('areaCanCollectPayment', false)
            ->set('areaIsDelivery', false)
            ->set('areaSelectedUserIds', [$this->mechanicUser->id])
            ->call('saveArea')
            ->assertDispatched('notify');

        $area = Area::where('tenant_id', $this->tenant->id)->where('name', 'Diagnóstico y Presupuestos')->first();
        $this->assertNotNull($area);
        $this->assertEquals('presupuesto', $area->default_status);
        $this->assertTrue($area->can_generate_quotation);
        $this->assertTrue($area->can_view_quotation_amount);
        $this->assertFalse($area->can_collect_payment);
        $this->assertFalse($area->is_delivery);

        // Verifica la asignación de usuarios
        $this->assertTrue($area->users->contains('id', $this->mechanicUser->id));
    }

    public function test_can_edit_existing_area_and_update_user_assignments(): void
    {
        $area = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Mecánica Rápida',
            'default_status' => 'trabajando',
            'can_generate_quotation' => false,
            'can_view_quotation_amount' => false,
            'can_collect_payment' => false,
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(WorkshopSettings::class)
            ->call('openEditAreaModal', $area->id)
            ->set('areaName', 'Mecánica Rápida & Frenos')
            ->set('areaCanCollectPayment', true)
            ->set('areaSelectedUserIds', [$this->adminUser->id, $this->mechanicUser->id])
            ->call('saveArea')
            ->assertDispatched('notify');

        $updated = $area->fresh(['users']);
        $this->assertEquals('Mecánica Rápida & Frenos', $updated->name);
        $this->assertTrue($updated->can_collect_payment);
        $this->assertCount(2, $updated->users);
    }

    public function test_cannot_delete_area_if_it_has_active_work_orders(): void
    {
        $area = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Enderezado',
            'default_status' => 'trabajando',
        ]);

        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Juan Cliente',
            'phone' => '55554444',
        ]);

        $vehicle = Vehicle::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'plate' => 'P123XYZ',
            'owner_name' => 'Juan Cliente',
            'brand' => 'Toyota',
            'model' => 'Corolla',
        ]);

        WorkOrder::create([
            'tenant_id' => $this->tenant->id,
            'vehicle_id' => $vehicle->id,
            'current_area_id' => $area->id,
            'status' => 'trabajando',
            'payment_type' => 'particular',
        ]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(WorkshopSettings::class)
            ->call('confirmDeleteArea', $area->id)
            ->assertDispatched('notify');

        // No debe abrirse el modal de confirmación porque no está vacía
        $this->assertFalse($component->get('confirmingAreaDeletion'));

        // El área sigue existiendo en base de datos
        $this->assertDatabaseHas('areas', ['id' => $area->id]);
    }

    public function test_can_delete_area_when_it_is_empty_without_work_orders(): void
    {
        $emptyArea = Area::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Lavado y Detailing',
            'default_status' => 'revision',
        ]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(WorkshopSettings::class)
            ->call('confirmDeleteArea', $emptyArea->id);

        // Se abre el modal de confirmación
        $this->assertTrue($component->get('confirmingAreaDeletion'));
        $this->assertEquals($emptyArea->id, $component->get('areaToDeleteId'));

        // Confirma el borrado
        $component->call('deleteArea')
            ->assertDispatched('notify');

        // El área ya no debe existir
        $this->assertDatabaseMissing('areas', ['id' => $emptyArea->id]);
    }

    public function test_can_switch_tabs_and_update_workshop_details(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(WorkshopSettings::class)
            ->assertSet('activeTab', 'areas')
            ->call('setActiveTab', 'general')
            ->assertSet('activeTab', 'general')
            ->assertSee('Identificación y Contacto Oficial del Taller')
            ->set('name', 'Taller Los Campeones S.A.')
            ->set('phone', '5555-1234')
            ->set('email', 'campeones@taller.gt')
            ->call('save')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('tenants', [
            'id' => $this->tenant->id,
            'name' => 'Taller Los Campeones S.A.',
            'phone' => '5555-1234',
            'email' => 'campeones@taller.gt',
        ]);
    }

    public function test_can_manage_allowed_vehicle_types_and_presets(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(WorkshopSettings::class)
            ->assertSet('activeTab', 'areas')
            ->call('setActiveTab', 'vehicles')
            ->assertSet('activeTab', 'vehicles')
            ->assertSee('Tipos de Vehículos que Atiende la Empresa')
            ->call('selectOnlyMotos')
            ->assertSet('allowedVehicleTypes', ['pasola', 'moto'])
            ->call('saveVehicleTypes')
            ->assertDispatched('notify');

        $this->assertEquals(['pasola', 'moto'], $this->tenant->fresh()->allowed_vehicle_types);

        // Intento de guardar sin selección debe arrojar error
        Livewire::actingAs($this->adminUser)
            ->test(WorkshopSettings::class)
            ->call('clearVehicleTypes')
            ->assertSet('allowedVehicleTypes', [])
            ->call('saveVehicleTypes')
            ->assertDispatched('notify');

        // Los tipos permitidos no cambiaron al fallar la validación
        $this->assertEquals(['pasola', 'moto'], $this->tenant->fresh()->allowed_vehicle_types);

        // Seleccionar todos y guardar
        Livewire::actingAs($this->adminUser)
            ->test(WorkshopSettings::class)
            ->call('selectAllVehicleTypes')
            ->assertCount('allowedVehicleTypes', 12)
            ->call('saveVehicleTypes')
            ->assertDispatched('notify');

        $this->assertCount(12, $this->tenant->fresh()->allowed_vehicle_types);
    }
}
