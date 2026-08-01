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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('base_unit');
            $table->decimal('current_stock', 12, 3)->default(0);
            $table->decimal('current_price_per_base_unit', 12, 4)->default(0);
            $table->decimal('low_stock_threshold', 12, 3)->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
