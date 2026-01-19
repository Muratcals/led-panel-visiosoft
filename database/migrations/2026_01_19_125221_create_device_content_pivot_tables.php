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
        // Cihaz - Video ilişki tablosu
        Schema::create('device_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->integer('order')->default(0); // Gösterim sırası
            $table->boolean('is_active')->default(true); // Aktif mi?
            $table->timestamps();
            
            $table->unique(['device_id', 'video_id']);
        });

        // Cihaz - Reklam Slayt ilişki tablosu
        Schema::create('device_ad_slide', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('ad_slide_id')->constrained()->onDelete('cascade');
            $table->integer('order')->default(0); // Gösterim sırası
            $table->boolean('is_active')->default(true); // Aktif mi?
            $table->timestamps();
            
            $table->unique(['device_id', 'ad_slide_id']);
        });

        // Cihaz - Fiyat Tarifesi ilişki tablosu
        Schema::create('device_price_tariff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('price_tariff_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true); // Aktif mi?
            $table->timestamps();
            
            $table->unique(['device_id', 'price_tariff_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_price_tariff');
        Schema::dropIfExists('device_ad_slide');
        Schema::dropIfExists('device_video');
    }
};
