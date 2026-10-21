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
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'fuel_type')) {
                $table->string('fuel_type', 30)->default('gasolina')->after('type');
            }
        });

        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'fuel_type')) {
                $table->string('fuel_type', 30)->default('gasolina')->after('payment_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'fuel_type')) {
                $table->dropColumn('fuel_type');
            }
        });

        Schema::table('work_orders', function (Blueprint $table) {
            if (Schema::hasColumn('work_orders', 'fuel_type')) {
                $table->dropColumn('fuel_type');
            }
        });
    }
};
