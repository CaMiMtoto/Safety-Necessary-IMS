<?php

use App\Models\Product;
use App\Models\SaleDelivery;
use App\Models\SaleOrderItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_delivery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SaleDelivery::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(SaleOrderItem::class)->constrained();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_delivery_items');
    }
};
