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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('current_area_id')->nullable()->constrained('areas')->onDelete('set null');
            
            $table->enum('status', ['recepcion', 'presupuesto', 'autorizado', 'en_proceso', 'finalizado', 'entregado'])->default('recepcion');
            $table->enum('payment_type', ['aseguradora', 'particular']);
            $table->string('insurance_company')->nullable();
            
            $table->integer('mileage')->nullable();
            $table->enum('fuel_level', ['E', '1/4', '1/2', '3/4', 'F'])->nullable();
            
            $table->timestamp('received_at')->useCurrent();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
