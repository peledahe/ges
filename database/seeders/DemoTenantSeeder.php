<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Area;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\ChecklistTemplate;

class DemoTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Limpiar datos antiguos de forma segura según el driver de base de datos
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        DB::table('work_order_photos')->truncate();
        DB::table('work_order_checklists')->truncate();
        DB::table('work_orders')->truncate();
        DB::table('vehicles')->truncate();
        DB::table('customers')->truncate();
        DB::table('area_user')->truncate();
        DB::table('areas')->truncate();
        DB::table('checklist_templates')->truncate();
        DB::table('users')->truncate();
        DB::table('tenants')->truncate();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // 2. Crear Super Administrador (Root)
        User::create([
            'name' => 'Super Administrador (Root)',
            'email' => 'root@ges.com',
            'password' => Hash::make('password'),
            'role' => 'root',
            'tenant_id' => null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Root GES Local',
            'email' => 'root@ges.local',
            'password' => Hash::make('password'),
            'role' => 'root',
            'tenant_id' => null,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 3. Crear Tenant de Pruebas / Demo
        $demoTenant = Tenant::create([
            'name' => 'Taller AutoFix Demo',
            'slug' => 'taller-demo',
            'admin_name' => 'Carlos Mendoza',
            'email' => 'admin@taller-demo.com',
            'phone' => '+502 5555-0199',
            'address' => 'Calzada Roosevelt 14-20, Zona 11, Ciudad de Guatemala',
            'is_active' => true,
            'users_limit' => 20,
            'allowed_vehicle_types' => [
                'pasola',
                'moto',
                'hashback',
                'sedan',
                'suv',
                'pickup',
                'pickup_double',
                'van',
                'camion'
            ],
        ]);

        // 4. Crear Áreas del Taller Demo
        $areaRecepcion = Area::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Recepción',
            'description' => 'Ingreso de vehículos, inventario y checklist inicial',
            'default_status' => 'recibido',
            'order' => 1,
            'can_generate_quotation' => true,
            'can_view_quotation_amount' => true,
            'can_collect_payment' => true,
            'is_delivery' => false,
        ]);

        $areaPresupuesto = Area::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Presupuesto y Diagnóstico',
            'description' => 'Evaluación técnica, cotización y confirmación de repuestos',
            'default_status' => 'presupuesto',
            'order' => 2,
            'can_generate_quotation' => true,
            'can_view_quotation_amount' => true,
            'can_collect_payment' => false,
            'is_delivery' => false,
        ]);

        $areaMecanica = Area::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Mecánica General',
            'description' => 'Mantenimiento preventivo, frenos, motor y suspensión',
            'default_status' => 'trabajando',
            'order' => 3,
            'can_generate_quotation' => false,
            'can_view_quotation_amount' => true,
            'can_collect_payment' => false,
            'is_delivery' => false,
        ]);

        $areaPintura = Area::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Enderezado y Pintura',
            'description' => 'Reparación de carrocería, hojalatería y detallado',
            'default_status' => 'trabajando',
            'order' => 4,
            'can_generate_quotation' => false,
            'can_view_quotation_amount' => false,
            'can_collect_payment' => false,
            'is_delivery' => false,
        ]);

        $areaCalidad = Area::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Control de Calidad',
            'description' => 'Prueba de ruta, limpieza y verificación final',
            'default_status' => 'revision',
            'order' => 5,
            'can_generate_quotation' => false,
            'can_view_quotation_amount' => true,
            'can_collect_payment' => false,
            'is_delivery' => false,
        ]);

        $areaEntrega = Area::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Terminado y Entrega',
            'description' => 'Vehículo listo, cobro de saldo y entrega al cliente',
            'default_status' => 'terminado',
            'order' => 6,
            'can_generate_quotation' => false,
            'can_view_quotation_amount' => true,
            'can_collect_payment' => true,
            'is_delivery' => true,
        ]);

        $allAreas = [$areaRecepcion, $areaPresupuesto, $areaMecanica, $areaPintura, $areaCalidad, $areaEntrega];

        // 5. Crear Usuarios de Demo con Claves Aleatorias Protegidas
        $adminPass = 'Adm!' . Str::random(8) . '7';
        $receptionPass = 'Rec!' . Str::random(8) . '9';
        $mechanicPass = 'Tec!' . Str::random(8) . '3';
        $clientPass = 'Cli!' . Str::random(8) . '5';

        $admin = User::create([
            'name' => 'Carlos Mendoza (Administrador)',
            'email' => 'admin@taller-demo.com',
            'password' => Hash::make($adminPass),
            'demo_password' => $adminPass,
            'is_demo' => true,
            'tenant_id' => $demoTenant->id,
            'role' => 'admin',
            'is_active' => true,
            'can_view_contact_info' => true,
            'email_verified_at' => now(),
        ]);
        $admin->areas()->sync(collect($allAreas)->pluck('id'));

        $reception = User::create([
            'name' => 'Lucía Ramos (Recepción)',
            'email' => 'recepcion@taller-demo.com',
            'password' => Hash::make($receptionPass),
            'demo_password' => $receptionPass,
            'is_demo' => true,
            'tenant_id' => $demoTenant->id,
            'role' => 'reception',
            'is_active' => true,
            'can_view_contact_info' => true,
            'email_verified_at' => now(),
        ]);
        $reception->areas()->sync([$areaRecepcion->id, $areaEntrega->id]);

        $mechanic = User::create([
            'name' => 'Mario Estrada (Técnico / Mecánico)',
            'email' => 'mecanico@taller-demo.com',
            'password' => Hash::make($mechanicPass),
            'demo_password' => $mechanicPass,
            'is_demo' => true,
            'tenant_id' => $demoTenant->id,
            'role' => 'area_manager',
            'is_active' => true,
            'can_view_contact_info' => false,
            'email_verified_at' => now(),
        ]);
        $mechanic->areas()->sync([$areaMecanica->id, $areaPresupuesto->id, $areaCalidad->id]);

        $client = User::create([
            'name' => 'Alejandro Morales (Cliente)',
            'email' => 'cliente@taller-demo.com',
            'password' => Hash::make($clientPass),
            'demo_password' => $clientPass,
            'is_demo' => true,
            'tenant_id' => $demoTenant->id,
            'role' => 'client',
            'is_active' => true,
            'can_view_contact_info' => false,
            'email_verified_at' => now(),
        ]);

        // 6. Clientes y Vehículos Demo
        $customer1 = Customer::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Alejandro Morales',
            'phone' => '5555-1234',
            'email' => 'cliente@taller-demo.com',
            'nit' => '1234567-8',
            'address' => 'Zona 14, Ciudad de Guatemala',
        ]);

        $customer2 = Customer::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Patricia Gómez',
            'phone' => '5555-4321',
            'email' => 'patricia.gomez@gmail.com',
            'nit' => '8765432-1',
            'address' => 'Carretera a El Salvador, Km 14',
        ]);

        $v1 = Vehicle::create([
            'tenant_id' => $demoTenant->id,
            'customer_id' => $customer1->id,
            'plate' => 'P-458XYZ',
            'brand' => 'Toyota',
            'model' => 'Hilux Revo',
            'year' => 2022,
            'color' => 'Gris Metálico',
            'type' => 'pickup_double',
            'doors_qty' => 4,
            'owner_name' => $customer1->name,
            'owner_phone' => $customer1->phone,
            'owner_email' => $customer1->email,
            'owner_nit' => $customer1->nit,
            'owner_address' => $customer1->address,
        ]);

        $v2 = Vehicle::create([
            'tenant_id' => $demoTenant->id,
            'customer_id' => $customer1->id,
            'plate' => 'P-123ABC',
            'brand' => 'Honda',
            'model' => 'Civic Turbo',
            'year' => 2021,
            'color' => 'Blanco Perla',
            'type' => 'sedan',
            'doors_qty' => 4,
            'owner_name' => $customer1->name,
            'owner_phone' => $customer1->phone,
            'owner_email' => $customer1->email,
            'owner_nit' => $customer1->nit,
            'owner_address' => $customer1->address,
        ]);

        $v3 = Vehicle::create([
            'tenant_id' => $demoTenant->id,
            'customer_id' => $customer2->id,
            'plate' => 'P-890DEF',
            'brand' => 'Mazda',
            'model' => 'CX-5 AWD',
            'year' => 2023,
            'color' => 'Rojo Soul',
            'type' => 'suv',
            'doors_qty' => 5,
            'owner_name' => $customer2->name,
            'owner_phone' => $customer2->phone,
            'owner_email' => $customer2->email,
            'owner_nit' => $customer2->nit,
            'owner_address' => $customer2->address,
        ]);

        // 7. Órdenes de Trabajo de Demostración
        WorkOrder::create([
            'tenant_id' => $demoTenant->id,
            'vehicle_id' => $v1->id,
            'current_area_id' => $areaMecanica->id,
            'status' => 'trabajando',
            'payment_type' => 'particular',
            'fuel_type' => 'diesel',
            'fuel_level' => '1/2',
            'mileage' => 48500,
            'total_amount' => 3450.00,
            'paid_amount' => 1500.00,
            'payment_status' => 'parcial',
            'payment_method' => 'transferencia',
            'payment_reference' => 'TRANSF-77491',
            'notes' => "Requerimiento: Servicio mayor de 50,000 KM, cambio de aceite sintético, filtros de aire y combustible, revisión de frenos delanteros.\nDiagnóstico: Pastillas de freno con 20% de vida útil.",
            'received_at' => now()->subDays(2),
        ]);

        WorkOrder::create([
            'tenant_id' => $demoTenant->id,
            'vehicle_id' => $v2->id,
            'current_area_id' => $areaPresupuesto->id,
            'status' => 'presupuesto',
            'payment_type' => 'particular',
            'fuel_type' => 'gasolina',
            'fuel_level' => '3/4',
            'mileage' => 32400,
            'total_amount' => 1850.00,
            'paid_amount' => 0.00,
            'payment_status' => 'pendiente',
            'notes' => "Requerimiento: Ruido en tren delantero al pasar por baches y vibración ligera en volante a más de 80 km/h.\nDiagnóstico: Cabezales y bujes con holgura.",
            'received_at' => now()->subDay(),
        ]);

        WorkOrder::create([
            'tenant_id' => $demoTenant->id,
            'vehicle_id' => $v3->id,
            'current_area_id' => $areaEntrega->id,
            'status' => 'terminado',
            'payment_type' => 'particular',
            'fuel_type' => 'gasolina',
            'fuel_level' => 'F',
            'mileage' => 19200,
            'total_amount' => 650.00,
            'paid_amount' => 650.00,
            'payment_status' => 'pagado',
            'payment_method' => 'tarjeta',
            'payment_reference' => 'POS-99214',
            'paid_at' => now()->subHours(2),
            'notes' => "Requerimiento: Alineación computarizada, balanceo de 4 ruedas y cambio de plumillas limpiaparabrisas.\nEstado: Servicio completado exitosamente. Vehículo lavado y listo para entrega.",
            'received_at' => now()->subHours(6),
        ]);

        // 8. Plantilla Checklist Estándar
        ChecklistTemplate::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Checklist General de Ingreso',
            'items' => json_encode([
                [
                    'section' => 'Exterior y Luces',
                    'items' => ['Luces delanteras', 'Luces traseras', 'Plumillas', 'Espejos laterales', 'Antena']
                ],
                [
                    'section' => 'Equipamiento de Emergencia',
                    'items' => ['Llanta de repuesto', 'Tricket / Gato', 'Llave de chucho', 'Triángulos', 'Extintor']
                ],
                [
                    'section' => 'Interior y Documentos',
                    'items' => ['Radio / Pantalla', 'Encendedor / USB', 'Alfombras', 'Tarjeta de circulación']
                ]
            ]),
        ]);
    }
}
