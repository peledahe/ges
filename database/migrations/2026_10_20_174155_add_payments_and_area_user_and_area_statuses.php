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
        // 1. Campos de pago y entrega en work_orders
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->nullable()->after('insurance_company');
            }
            if (!Schema::hasColumn('work_orders', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('work_orders', 'payment_status')) {
                $table->string('payment_status', 30)->default('pendiente')->after('paid_amount');
            }
            if (!Schema::hasColumn('work_orders', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('work_orders', 'payment_reference')) {
                $table->string('payment_reference', 255)->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('work_orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_reference');
            }
        });

        // 2. Campos en areas para vinculación de estado y bandera de entrega
        Schema::table('areas', function (Blueprint $table) {
            if (!Schema::hasColumn('areas', 'default_status')) {
                $table->string('default_status', 50)->default('trabajando')->after('name');
            }
            if (!Schema::hasColumn('areas', 'is_delivery')) {
                $table->boolean('is_delivery')->default(false)->after('default_status');
            }
        });

        // 3. Tabla pivote area_user para asignar 1 o múltiples áreas por usuario
        if (!Schema::hasTable('area_user')) {
            Schema::create('area_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['area_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_user');

        Schema::table('areas', function (Blueprint $table) {
            if (Schema::hasColumn('areas', 'default_status')) {
                $table->dropColumn('default_status');
            }
            if (Schema::hasColumn('areas', 'is_delivery')) {
                $table->dropColumn('is_delivery');
            }
        });

        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'total_amount',
                'paid_amount',
                'payment_status',
                'payment_method',
                'payment_reference',
                'paid_at',
            ]);
        });
    }
};
