<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Area;
use App\Models\Vehicle;
use App\Models\ChecklistTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Tenants
        $tenantA = Tenant::create([
            'name' => 'Taller Mecánico A',
            'slug' => 'taller-a',
            'admin_name' => 'Admin A',
            'email' => 'admin@tallera.com',
            'phone' => '12345678',
            'address' => 'Zona 1',
        ]);

        $tenantB = Tenant::create([
            'name' => 'Taller Mecánico B',
            'slug' => 'taller-b',
            'admin_name' => 'Admin B',
            'email' => 'admin@tallerb.com',
            'phone' => '87654321',
            'address' => 'Zona 10',
        ]);

        // 2. Create Users
        User::create([
            'name' => 'Admin A',
            'email' => 'admin@tallera.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenantA->id,
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin B',
            'email' => 'admin@tallerb.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenantB->id,
            'role' => 'admin',
        ]);

        // 3. Create Areas (Using Manual ID to simulate context)
        // Note: Models have BelongsToTenant trait, so we need to mock Session or manually set tenant_id if managing from outside.
        // But since we are seeding, we can temporarily disable scope or forcefully set it.
        // The trait sets tenant_id on creating ONLY if Session has it. If we pass it, it should be fine? 
        // Let's test if passing tenant_id overrides the auto-set.

        Area::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Recepción',
            'description' => 'Área de ingreso',
        ]);

        Area::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Mecánica General',
        ]);

        Area::create([
            'tenant_id' => $tenantB->id,
            'name' => 'Recepción', // Same name, different tenant
        ]);

        // 4. Create Checklist Template
        ChecklistTemplate::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Recepción Estándar',
            'items' => json_encode([
                [
                    'section' => 'Parte Trasera/Baúl',
                    'items' => ['Emblema', 'Luz de Placa', 'Tapicería de Baúl', 'Herramientas', 'Llave de Chucho', 'Tricket', 'Llanta de Repuesto']
                ],
                [
                    'section' => 'Parte Frontal',
                    'items' => ['Antena', 'Emblema', 'Spoiler Delantero', 'Luces Delanteras', 'Plumillas']
                ]
            ]),
        ]);

        // 5. Create Vehicle for Tenant A
        // We set Session to test Scope application later
        Session::put('tenant_id', $tenantA->id);
        
        // This vehicle should belong to Tenant A automatically if we used the trait correctly and session is set.
        // But let's be explicit in seeder to avoid confusion.
        Vehicle::create([
            'tenant_id' => $tenantA->id,
            'plate' => '324GkR',
            'brand' => 'Toyota',
            'model' => 'Rav 4',
            'year' => 2015,
            'color' => 'Verde',
            'owner_name' => 'Juan Perez',
            'doors_qty' => 5,
        ]);
    }
}
