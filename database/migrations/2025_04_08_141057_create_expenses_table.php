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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\ExpenseCategory::class)->constrained();
            $table->string('description')->nullable();
            $table->decimal('amount', 18, 0);
            $table->date('date');
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->string('attachment')->nullable();
            $table->timestamps();
        });
    }
//    0788489434

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
