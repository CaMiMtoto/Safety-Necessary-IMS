<?php

use App\Models\SaleOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_deliveries', function (Blueprint $table) {
            $table->id();
            $table->dateTime('delivery_date');
            $table->string('delivery_address')->nullable();
            $table->string('delivery_status')->default('pending');
            $table->string('delivered_by')->nullable();
            $table->foreignIdFor(SaleOrder::class)->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_deliveries');
    }
};
