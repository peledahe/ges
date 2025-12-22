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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('plate')->index();
            $table->string('vin')->nullable();
            $table->string('brand');
            $table->string('model');
            $table->string('line')->nullable();
            $table->string('type')->nullable(); // Sedan, SUV, etc
            $table->string('color')->nullable();
            $table->integer('year')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('chassis_number')->nullable();
            $table->integer('doors_qty')->nullable();
            $table->integer('cc')->nullable();
            
            // Owner Information
            $table->string('owner_name');
            $table->string('owner_phone')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_nit')->nullable();
            $table->text('owner_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
