<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ReceptionForm;
use App\Livewire\TenantManagement;
use App\Models\Area;
use App\Models\Scopes\TenantScope;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class TenantManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_root_user_cannot_access_tenant_management(): void
    {
        $tenant = Tenant::create([
            'name' => 'Taller Normal',
            'slug' => 'taller-normal',
            'admin_name' => 'Admin Normal',
            'email' => 'admin@normal.com',
        ]);

        $adminUser = User::create([
            'name' => 'Admin Regular',
            'email' => 'admin@normal.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->actingAs($adminUser)->get(route('admin.tenants'));
        $response->assertStatus(403);
    }

    public function test_root_user_can_access_tenant_management_and_dashboard(): void
    {
        $rootUser = User::create([
            'name' => 'Admin Root',
            'email' => 'root@ges.local',
            'password' => Hash::make('password'),
            'role' => 'root',
            'tenant_id' => null,
        ]);

        $response = $this->actingAs($rootUser)->get(route('admin.tenants'));
        $response->assertStatus(200);
        $response->assertSee('Administración de Talleres (Tenants)');
    }

    public function test_root_user_can_create_tenant_with_allowed_vehicle_types(): void
    {
        $rootUser = User::create([
            'name' => 'Admin Root',
            'email' => 'root@ges.local',
            'password' => Hash::make('password'),
            'role' => 'root',
            'tenant_id' => null,
        ]);

        Livewire::actingAs($rootUser)
            ->test(TenantManagement::class)
            ->call('createTenant')
            ->set('name', 'Taller Especializado en Motos')
            ->set('slug', 'taller-motos')
            ->set('admin_name', 'Carlos Moto')
            ->set('email', 'carlos@motos.com')
            ->set('phone', '55554444')
            ->set('address', 'Km 14 Carretera a El Salvador')
            ->set('allowed_vehicle_types', ['pasola', 'moto'])
            ->call('saveTenant')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('tenants', [
            'slug' => 'taller-motos',
            'name' => 'Taller Especializado en Motos',
            'admin_name' => 'Carlos Moto',
        ]);

        $createdTenant = Tenant::where('slug', 'taller-motos')->first();
        $this->assertEquals(['pasola', 'moto'], $createdTenant->allowed_vehicle_types);

        // Se debió crear automáticamente el área de Recepción
        $this->assertDatabaseHas('areas', [
            'tenant_id' => $createdTenant->id,
            'name' => 'Recepción',
        ]);
    }

    public function test_root_user_can_toggle_tenant_status(): void
    {
        $rootUser = User::create([
            'name' => 'Admin Root',
            'email' => 'root@ges.local',
            'password' => Hash::make('password'),
            'role' => 'root',
            'tenant_id' => null,
        ]);

        $tenant = Tenant::create([
            'name' => 'Taller Beta',
            'slug' => 'taller-beta',
            'admin_name' => 'Admin Beta',
            'email' => 'beta@taller.com',
            'is_active' => true,
        ]);

        Livewire::actingAs($rootUser)
            ->test(TenantManagement::class)
            ->call('toggleTenantStatus', $tenant->id)
            ->assertDispatched('notify');

        $this->assertFalse((bool) $tenant->fresh()->is_active);
    }

    public function test_root_user_can_manage_areas_for_a_tenant(): void
    {
        $rootUser = User::create([
            'name' => 'Admin Root',
            'email' => 'root@ges.local',
            'password' => Hash::make('password'),
            'role' => 'root',
            'tenant_id' => null,
        ]);

        $tenant = Tenant::create([
            'name' => 'Taller Gamma',
            'slug' => 'taller-gamma',
            'admin_name' => 'Admin Gamma',
            'email' => 'gamma@taller.com',
        ]);

        Livewire::actingAs($rootUser)
            ->test(TenantManagement::class)
            ->call('manageAreas', $tenant->id)
            ->set('newAreaName', 'Pintura y Horno')
            ->set('newAreaDescription', 'Cabina presurizada de pintura')
            ->call('addArea')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('areas', [
            'tenant_id' => $tenant->id,
            'name' => 'Pintura y Horno',
        ]);

        // Cargar áreas recomendadas
        Livewire::actingAs($rootUser)
            ->test(TenantManagement::class)
            ->call('manageAreas', $tenant->id)
            ->call('loadRecommendedAreas')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('areas', [
            'tenant_id' => $tenant->id,
            'name' => 'Diagnóstico y Presupuestos',
        ]);
    }

    public function test_reception_form_respects_tenant_assigned_vehicle_types(): void
    {
        // Tenant solo atiende Motos y Pasolas
        $tenantMotos = Tenant::create([
            'name' => 'Taller Solo Motos',
            'slug' => 'solo-motos',
            'admin_name' => 'Mario Moto',
            'email' => 'mario@motos.com',
            'allowed_vehicle_types' => ['pasola', 'moto'],
        ]);

        $staffUser = User::create([
            'name' => 'Recepcionista Motos',
            'email' => 'recepcion@motos.com',
            'password' => Hash::make('password'),
            'role' => 'recepcion',
            'tenant_id' => $tenantMotos->id,
        ]);

        session(['tenant_id' => $tenantMotos->id]);

        $component = Livewire::actingAs($staffUser)
            ->test(ReceptionForm::class);

        $allowed = $component->get('allowedVehicleTypes');
        $this->assertArrayHasKey('pasola', $allowed);
        $this->assertArrayHasKey('moto', $allowed);
        $this->assertArrayNotHasKey('sedan', $allowed);
        $this->assertArrayNotHasKey('cabezal', $allowed);
    }

    public function test_user_of_disabled_tenant_cannot_login_and_gets_error_message(): void
    {
        $tenant = Tenant::create([
            'name' => 'Taller Desactivado',
            'slug' => 'taller-desactivado',
            'admin_name' => 'Dueño Inactivo',
            'email' => 'taller@inactivo.com',
            'is_active' => false,
        ]);

        $user = User::create([
            'name' => 'Empleado Taller',
            'email' => 'empleado@inactivo.com',
            'password' => Hash::make('secret123'),
            'role' => 'mecanico',
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'empleado@inactivo.com',
            'password' => 'secret123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors([
            'email' => 'Su taller se encuentra deshabilitado por el momento. Por favor, comuníquese con el administrador del sistema.',
        ]);
    }

    public function test_authenticated_user_of_disabled_tenant_is_logged_out_by_middleware(): void
    {
        $tenant = Tenant::create([
            'name' => 'Taller Temporal',
            'slug' => 'taller-temporal',
            'admin_name' => 'Dueño',
            'email' => 'dueno@temporal.com',
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Empleado Activo',
            'email' => 'empleado@temporal.com',
            'password' => Hash::make('password'),
            'role' => 'mecanico',
            'tenant_id' => $tenant->id,
        ]);

        // Simular que el root desactiva el taller mientras el usuario tiene sesión
        $tenant->update(['is_active' => false]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Su taller se encuentra deshabilitado por el momento. Por favor, comuníquese con el administrador del sistema.',
        ]);
        $this->assertGuest();
    }

    public function test_user_of_enabled_tenant_can_login_normally(): void
    {
        $tenant = Tenant::create([
            'name' => 'Taller Activo OK',
            'slug' => 'taller-activo-ok',
            'admin_name' => 'Dueño Activo',
            'email' => 'dueno@activo.com',
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Empleado Activo OK',
            'email' => 'empleado@activo.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'empleado@activo.com',
            'password' => 'secret123',
        ]);

        $this->assertAuthenticatedAs($user);
    }
}
