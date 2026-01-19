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
        Schema::create('ad_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('icon')->default('📢'); // emoji or icon
            $table->integer('duration')->default(15); // seconds
            $table->string('background_color')->default('#0055ff');
            $table->boolean('is_active')->default(true);
            $table->enum('position', ['top', 'bottom'])->default('top');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_slides');
    }
};
