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
        // 1. Agregar exchange_rate y default_currency a tenants
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'exchange_rate')) {
                $table->decimal('exchange_rate', 8, 4)->default(7.8000)->after('users_limit');
            }
            if (!Schema::hasColumn('tenants', 'default_currency')) {
                $table->string('default_currency', 10)->default('GTQ')->after('exchange_rate');
            }
        });

        // 2. Agregar currency y payment_currency a work_orders
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'currency')) {
                $table->string('currency', 10)->default('GTQ')->after('paid_amount');
            }
            if (!Schema::hasColumn('work_orders', 'exchange_rate_used')) {
                $table->decimal('exchange_rate_used', 8, 4)->nullable()->after('currency');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['exchange_rate', 'default_currency']);
        });

        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['currency', 'exchange_rate_used']);
        });
    }
};
