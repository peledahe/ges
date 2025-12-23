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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('client')->after('email');
            }
            if (!Schema::hasColumn('users', 'area_id')) {
                $table->unsignedBigInteger('area_id')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'can_view_contact_info')) {
                $table->boolean('can_view_contact_info')->default(false)->after('area_id');
            }
            
            // Foreign key using string syntax for brevity, assuming standard naming
            // Or precise definition:
            // $table->foreign('area_id')->references('id')->on('areas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'area_id', 'can_view_contact_info']);
        });
    }
};
