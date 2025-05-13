<?php

use App\Models\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->foreignIdFor(\App\Models\SaleOrder::class)->constrained();
            $table->foreignIdFor(\App\Models\Customer::class)->constrained();
            $table->double('amount');
            $table->foreignIdFor(PaymentMethod::class)->constrained();
            $table->string('reference')->nullable();
            $table->string('attachment')->nullable();
            $table->string('status')->default('paid')->nullable();
            $table->string('note')->nullable();
            $table->string('currency')->default('RWF');
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->double('balance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_payments');
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};
