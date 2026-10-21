<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\ResetUserPassword;

class DemoUserProtectionTest extends TestCase
{
    use RefreshDatabase;

    private function createTenant(string $name, string $slug, string $email): Tenant
    {
        return Tenant::create([
            'name' => $name,
            'slug' => $slug,
            'admin_name' => 'Admin ' . $name,
            'email' => $email,
            'is_active' => true,
        ]);
    }

    public function test_demo_user_cannot_update_password()
    {
        $tenant = $this->createTenant('Demo Taller', 'demo-taller', 'demo@taller.com');

        $demoUser = User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
            'tenant_id' => $tenant->id,
            'is_demo' => true,
            'demo_password' => 'secret123',
        ]);

        $action = new UpdateUserPassword();

        $this->expectException(ValidationException::class);

        $action->update($demoUser, [
            'current_password' => 'secret123',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);
    }

    public function test_demo_user_cannot_reset_password()
    {
        $tenant = $this->createTenant('Demo Taller', 'demo-taller', 'demo@taller.com');

        $demoUser = User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
            'tenant_id' => $tenant->id,
            'is_demo' => true,
            'demo_password' => 'secret123',
        ]);

        $action = new ResetUserPassword();

        $this->expectException(ValidationException::class);

        $action->reset($demoUser, [
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);
    }

    public function test_regular_user_can_update_password()
    {
        $tenant = $this->createTenant('Regular Taller', 'regular-taller', 'regular@taller.com');

        $user = User::create([
            'name' => 'Regular User',
            'email' => 'regular@taller.com',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
            'tenant_id' => $tenant->id,
            'is_demo' => false,
        ]);

        $this->actingAs($user);

        $action = new UpdateUserPassword();

        $action->update($user, [
            'current_password' => 'secret123',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $user->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('NewPassword123!', $user->password));
    }

    public function test_demo_tenant_user_management_is_read_only()
    {
        $demoTenant = $this->createTenant('Taller Demo', 'taller-demo', 'demo@taller.com');

        $demoAdmin = User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@demo.com',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
            'tenant_id' => $demoTenant->id,
            'is_demo' => true,
        ]);

        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target@demo.com',
            'password' => bcrypt('secret123'),
            'role' => 'manager',
            'tenant_id' => $demoTenant->id,
            'is_demo' => false,
        ]);

        $this->actingAs($demoAdmin);

        // Can view the page
        $response = $this->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertSee('Entorno de Demostración — Modo Solo Lectura');
        $response->assertSee('Ver Detalle');

        // Cannot create
        \Livewire\Livewire::test(\App\Livewire\UserManagement::class)
            ->assertSet('isDemoTenant', true)
            ->call('create')
            ->assertDispatched('notify')
            ->assertSet('managingUserObject', false);

        // Cannot save
        \Livewire\Livewire::test(\App\Livewire\UserManagement::class)
            ->set('userId', $targetUser->id)
            ->set('name', 'Modified Name')
            ->call('save')
            ->assertDispatched('notify');

        $targetUser->refresh();
        $this->assertEquals('Target User', $targetUser->name);

        // Cannot delete
        \Livewire\Livewire::test(\App\Livewire\UserManagement::class)
            ->call('confirmDeletion', $targetUser->id)
            ->assertDispatched('notify')
            ->assertSet('confirmingUserDeletion', false);

        \Livewire\Livewire::test(\App\Livewire\UserManagement::class)
            ->set('userId', $targetUser->id)
            ->call('deleteUser')
            ->assertDispatched('notify');

        $this->assertDatabaseHas('users', ['id' => $targetUser->id]);
    }
}

