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
        Schema::table('areas', function (Blueprint $table) {
            if (!Schema::hasColumn('areas', 'can_collect_payment')) {
                $table->boolean('can_collect_payment')->default(false)->after('can_view_quotation_amount');
            }
        });

        // Habilitar cobro por defecto en áreas de entrega o recepción
        \Illuminate\Support\Facades\DB::table('areas')
            ->where(function ($q) {
                $q->where('is_delivery', true)
                  ->orWhere('name', 'like', '%entreg%')
                  ->orWhere('name', 'like', '%recep%')
                  ->orWhere('name', 'like', '%caja%');
            })
            ->update(['can_collect_payment' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            if (Schema::hasColumn('areas', 'can_collect_payment')) {
                $table->dropColumn('can_collect_payment');
            }
        });
    }
};
