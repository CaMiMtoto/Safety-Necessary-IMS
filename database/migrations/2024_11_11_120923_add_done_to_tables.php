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
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\User::class,'done_by')->nullable()->constrained('users');
        });
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\User::class,'done_by')->nullable()->constrained('users');
        });
        Schema::table('sale_deliveries', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\User::class,'done_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\User::class);
        });
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\User::class);
        });
        Schema::table('sale_deliveries', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\User::class);
        });
    }
};
