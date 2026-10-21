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
            if (!Schema::hasColumn('areas', 'can_generate_quotation')) {
                $table->boolean('can_generate_quotation')->default(false)->after('is_delivery');
            }
            if (!Schema::hasColumn('areas', 'can_view_quotation_amount')) {
                $table->boolean('can_view_quotation_amount')->default(true)->after('can_generate_quotation');
            }
        });

        // Activar can_generate_quotation en áreas que correspondan a presupuestos/diagnóstico
        \Illuminate\Support\Facades\DB::table('areas')
            ->where(function ($q) {
                $q->where('name', 'like', '%presupuest%')
                  ->orWhere('name', 'like', '%diagnost%')
                  ->orWhere('name', 'like', '%cotiz%')
                  ->orWhere('default_status', 'presupuesto');
            })
            ->update(['can_generate_quotation' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            if (Schema::hasColumn('areas', 'can_view_quotation_amount')) {
                $table->dropColumn('can_view_quotation_amount');
            }
            if (Schema::hasColumn('areas', 'can_generate_quotation')) {
                $table->dropColumn('can_generate_quotation');
            }
        });
    }
};
