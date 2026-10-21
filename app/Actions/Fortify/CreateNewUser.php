<?php

namespace App\Actions\Fortify;

use App\Mail\WelcomeNewTenantMail;
use App\Models\Area;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user and their workshop tenant.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'workshop_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'workshop_name.required' => 'El nombre de la empresa o taller es obligatorio.',
            'name.required' => 'El nombre del administrador es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'password.required' => 'La contraseña es obligatoria.',
        ])->validate();

        return DB::transaction(function () use ($input) {
            // 1. Generar slug único para el nuevo tenant
            $baseSlug = Str::slug($input['workshop_name']);
            if (empty($baseSlug)) {
                $baseSlug = 'taller-' . Str::lower(Str::random(6));
            }
            $slug = $baseSlug;
            $counter = 1;
            while (Tenant::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-" . ($counter++);
            }

            // 2. Crear el Tenant con 15 días de prueba gratuita
            $tenant = Tenant::create([
                'name' => trim($input['workshop_name']),
                'slug' => $slug,
                'admin_name' => trim($input['name']),
                'email' => trim($input['email']),
                'phone' => !empty($input['phone']) ? trim($input['phone']) : null,
                'is_active' => true,
                'trial_ends_at' => now()->addDays(15),
                'users_limit' => 5,
                'allowed_vehicle_types' => array_keys(Tenant::ALL_VEHICLE_TYPES),
                'exchange_rate' => 7.80,
                'default_currency' => 'GTQ',
            ]);

            // 3. Crear áreas recomendadas estándar para el flujo de trabajo inicial del taller
            $defaultAreas = [
                ['name' => 'Recepción', 'description' => 'Área de ingreso, inventario y checklist.', 'default_status' => 'recibido', 'is_delivery' => false, 'can_generate_quotation' => false, 'can_view_quotation_amount' => true, 'can_collect_payment' => true, 'order' => 1],
                ['name' => 'Presupuesto y Diagnóstico', 'description' => 'Evaluación técnica y cotizaciones.', 'default_status' => 'presupuesto', 'is_delivery' => false, 'can_generate_quotation' => true, 'can_view_quotation_amount' => true, 'can_collect_payment' => false, 'order' => 2],
                ['name' => 'Mecánica General', 'description' => 'Reparaciones y mantenimientos mecánicos.', 'default_status' => 'trabajando', 'is_delivery' => false, 'can_generate_quotation' => false, 'can_view_quotation_amount' => false, 'can_collect_payment' => false, 'order' => 3],
                ['name' => 'Enderezado y Pintura', 'description' => 'Carrocería, pintura y detalles estéticos.', 'default_status' => 'trabajando', 'is_delivery' => false, 'can_generate_quotation' => false, 'can_view_quotation_amount' => false, 'can_collect_payment' => false, 'order' => 4],
                ['name' => 'Control de Calidad', 'description' => 'Inspección final y pruebas de ruta.', 'default_status' => 'revision', 'is_delivery' => false, 'can_generate_quotation' => false, 'can_view_quotation_amount' => false, 'can_collect_payment' => false, 'order' => 5],
                ['name' => 'Terminado y Entrega', 'description' => 'Vehículos listos para entrega al cliente.', 'default_status' => 'terminado', 'is_delivery' => true, 'can_generate_quotation' => false, 'can_view_quotation_amount' => true, 'can_collect_payment' => true, 'order' => 6],
            ];

            foreach ($defaultAreas as $areaData) {
                $areaData['tenant_id'] = $tenant->id;
                Area::create($areaData);
            }

            // 4. Crear el usuario Administrador del nuevo taller
            $user = User::create([
                'name' => trim($input['name']),
                'email' => trim($input['email']),
                'password' => Hash::make($input['password']),
                'role' => 'admin',
                'tenant_id' => $tenant->id,
                'is_active' => true,
            ]);

            // Guardar tenant_id en sesión para acceso inmediato
            session(['tenant_id' => $tenant->id]);

            // 5. Enviar correo de bienvenida con credenciales registradas y prueba de 15 días
            try {
                Mail::to($user->email)->send(new WelcomeNewTenantMail($tenant, $user, $input['password']));
            } catch (\Throwable $e) {
                Log::error("No se pudo enviar el correo de bienvenida a {$user->email}: " . $e->getMessage());
            }

            return $user;
        });
    }
}
