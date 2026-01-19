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
        Schema::create('price_tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('time_range'); // e.g., "0-1 Saat", "1-2 Saat"
            $table->decimal('price', 10, 2); // e.g., 150.00
            $table->integer('order')->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_highlighted')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_tariffs');
    }
};
