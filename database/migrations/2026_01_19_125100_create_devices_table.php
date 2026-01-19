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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Cihaz adı (örn: "AVM Giriş Paneli")
            $table->string('device_code')->unique(); // Benzersiz cihaz kodu (örn: "LED-001")
            $table->string('location')->nullable(); // Cihaz konumu (örn: "İstanbul - Kadıköy AVM")
            $table->string('ip_address')->nullable(); // Cihaz IP adresi
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active'); // Cihaz durumu
            $table->timestamp('last_sync_at')->nullable(); // Son senkronizasyon zamanı
            $table->json('settings')->nullable(); // Cihaz özel ayarları (JSON)
            $table->text('notes')->nullable(); // Notlar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
