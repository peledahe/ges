<?php

namespace Tests\Feature;

use App\Mail\WelcomeNewTenantMail;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TenantRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Nombre de la Empresa o Taller');
        $response->assertSee('Nombre del Administrador');
        $response->assertSee('15 DÍAS DE PRUEBA');
    }

    public function test_new_user_registration_creates_tenant_with_15_days_trial_areas_and_sends_welcome_email(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'workshop_name' => 'Taller AutoSpeed Express',
            'name' => 'Carlos Administrador',
            'email' => 'carlos@autospeed.com',
            'phone' => '5555-9876',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();

        // 1. Verificar creación del Tenant
        $tenant = Tenant::where('email', 'carlos@autospeed.com')->first();
        $this->assertNotNull($tenant);
        $this->assertEquals('Taller AutoSpeed Express', $tenant->name);
        $this->assertEquals('taller-autospeed-express', $tenant->slug);
        $this->assertEquals('Carlos Administrador', $tenant->admin_name);
        $this->assertEquals('5555-9876', $tenant->phone);
        $this->assertTrue($tenant->is_active);
        $this->assertNotNull($tenant->trial_ends_at);
        $this->assertTrue($tenant->onTrial());
        $this->assertGreaterThanOrEqual(14, $tenant->trialDaysRemaining());

        // 2. Verificar que se crearon las áreas iniciales del taller
        $this->assertCount(6, $tenant->areas);
        $this->assertTrue($tenant->areas->contains('name', 'Recepción'));
        $this->assertTrue($tenant->areas->contains('name', 'Presupuesto y Diagnóstico'));
        $this->assertTrue($tenant->areas->contains('name', 'Mecánica General'));
        $this->assertTrue($tenant->areas->contains('name', 'Terminado y Entrega'));

        // 3. Verificar creación del usuario administrador
        $user = User::where('email', 'carlos@autospeed.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Carlos Administrador', $user->name);
        $this->assertEquals('admin', $user->role);
        $this->assertEquals($tenant->id, $user->tenant_id);
        $this->assertTrue($user->is_active);

        // 4. Verificar envío del correo de bienvenida con las credenciales
        Mail::assertSent(WelcomeNewTenantMail::class, function ($mail) use ($user, $tenant) {
            $rendered = $mail->render();

            return $mail->hasTo('carlos@autospeed.com')
                && $mail->tenant->id === $tenant->id
                && $mail->user->id === $user->id
                && $mail->password === 'password123'
                && str_contains($rendered, 'carlos@autospeed.com')
                && str_contains($rendered, 'password123')
                && str_contains($rendered, 'Taller AutoSpeed Express');
        });

        // 5. Redirección al Dashboard
        $response->assertRedirect('/dashboard');
    }

    public function test_workshop_name_is_required_for_registration(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'workshop_name' => '',
            'name' => 'Carlos Administrador',
            'email' => 'carlos@autospeed.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['workshop_name']);
        $this->assertGuest();
        Mail::assertNothingSent();
    }
}
