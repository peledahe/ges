<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Expand enum to include both old and new values
        DB::statement("ALTER TABLE work_orders MODIFY COLUMN status ENUM('recepcion', 'recibido', 'presupuesto', 'autorizado', 'en_proceso', 'en_espera', 'trabajando', 'revision', 'finalizado', 'entregado', 'terminado') DEFAULT 'recibido'");
        
        // Step 2: Update existing data to new values
        DB::table('work_orders')->where('status', 'recepcion')->update(['status' => 'recibido']);
        DB::table('work_orders')->where('status', 'autorizado')->update(['status' => 'trabajando']);
        DB::table('work_orders')->where('status', 'en_proceso')->update(['status' => 'trabajando']);
        DB::table('work_orders')->where('status', 'finalizado')->update(['status' => 'terminado']);
        DB::table('work_orders')->where('status', 'entregado')->update(['status' => 'terminado']);
        
        // Step 3: Set final enum with only new values
        DB::statement("ALTER TABLE work_orders MODIFY COLUMN status ENUM('recibido', 'presupuesto', 'en_espera', 'trabajando', 'revision', 'terminado') DEFAULT 'recibido'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Expand to include both
        DB::statement("ALTER TABLE work_orders MODIFY COLUMN status ENUM('recepcion', 'recibido', 'presupuesto', 'autorizado', 'en_proceso', 'en_espera', 'trabajando', 'revision', 'finalizado', 'entregado', 'terminado') DEFAULT 'recepcion'");
        
        // Revert data
        DB::table('work_orders')->where('status', 'recibido')->update(['status' => 'recepcion']);
        DB::table('work_orders')->where('status', 'trabajando')->update(['status' => 'en_proceso']);
        DB::table('work_orders')->where('status', 'terminado')->update(['status' => 'finalizado']);
        
        // Set old enum
        DB::statement("ALTER TABLE work_orders MODIFY COLUMN status ENUM('recepcion', 'presupuesto', 'autorizado', 'en_proceso', 'finalizado', 'entregado') DEFAULT 'recepcion'");
    }
};
