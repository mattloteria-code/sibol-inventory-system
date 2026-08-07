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
        Schema::create('production_batch_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained()->restrictOnDelete();
            $table->foreignId('ingredient_purchase_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('quantity_used', 12, 3);
            $table->decimal('unit_price_at_time', 12, 4);
            $table->decimal('subtotal_cost', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batch_ingredients');
    }
};
