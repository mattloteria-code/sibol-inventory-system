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
        Schema::create('ingredient_purchases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ingredient_id')->constrained()->restrictOnDelete();
            $table->string('purchase_unit');
            $table->decimal('purchase_unit_quantity', 12, 3);
            $table->decimal('conversion_to_base', 12, 4);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->decimal('base_units_added', 12, 3);
            $table->decimal('price_per_base_unit', 12, 4);
            $table->decimal('remaining_quantity', 12, 3);
            $table->string('supplier')->nullable();
            $table->date('purchase_date');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_purchases');
    }
};
