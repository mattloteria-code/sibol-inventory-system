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
        Schema::create('ingredient_price_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->decimal('old_price', 12, 4);
            $table->decimal('new_price', 12, 4);
            $table->string('reason')->nullable();
            $table->timestamp('changed_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_price_histories');
    }
};
