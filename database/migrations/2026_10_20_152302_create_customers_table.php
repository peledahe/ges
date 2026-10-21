<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('nit')->nullable()->index();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('tenant_id')->constrained('customers')->nullOnDelete();
        });

        // Migrar clientes existentes desde los registros de vehicles
        \Illuminate\Support\Facades\DB::table('vehicles')->get()->each(function ($vehicle) {
            if (!empty($vehicle->owner_name)) {
                $customer = \Illuminate\Support\Facades\DB::table('customers')
                    ->where('tenant_id', $vehicle->tenant_id)
                    ->where(function ($q) use ($vehicle) {
                        if (!empty($vehicle->owner_phone)) {
                            $q->orWhere('phone', $vehicle->owner_phone);
                        }
                        if (!empty($vehicle->owner_email)) {
                            $q->orWhere('email', $vehicle->owner_email);
                        }
                        if (!empty($vehicle->owner_nit)) {
                            $q->orWhere('nit', $vehicle->owner_nit);
                        }
                        $q->orWhere('name', $vehicle->owner_name);
                    })
                    ->first();

                if (!$customer) {
                    $customerId = \Illuminate\Support\Facades\DB::table('customers')->insertGetId([
                        'tenant_id' => $vehicle->tenant_id,
                        'name' => $vehicle->owner_name,
                        'phone' => $vehicle->owner_phone,
                        'email' => $vehicle->owner_email,
                        'nit' => $vehicle->owner_nit ?? null,
                        'address' => $vehicle->owner_address ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $customerId = $customer->id;
                }

                \Illuminate\Support\Facades\DB::table('vehicles')->where('id', $vehicle->id)->update([
                    'customer_id' => $customerId,
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });

        Schema::dropIfExists('customers');
    }
};
