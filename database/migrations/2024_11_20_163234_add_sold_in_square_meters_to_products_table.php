<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('sold_in_square_meters')->default(false)->after('stock_quantity');
            $table->float('box_coverage')->nullable()->after('sold_in_square_meters')->comment('Coverage in square meters (m²)');
            $table->string('stock_unit_measure')->nullable()->after('unit_measure');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sold_in_square_meters');
            $table->dropColumn('box_coverage');
            $table->dropColumn('stock_unit_measure');
        });
    }
};
