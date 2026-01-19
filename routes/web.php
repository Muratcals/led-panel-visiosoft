<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\Api\DeviceController;

// Ana sayfa - redirect to display
Route::get('/', function () {
    return redirect('/display');
});

// LED Panel görünümü - ?device=DEVICE_CODE parametresi ile cihaz bazlı içerik
Route::get('/display', [DisplayController::class, 'index'])->name('display.index');

// API endpoint - JSON formatında veri (cihaz bazlı kontrol için)
Route::get('/api/display', [DisplayController::class, 'api'])->name('display.api');

// Cihaz API Endpoint'leri
Route::prefix('api/device')->group(function () {
    // Cihaz otomatik kayıt
    Route::post('register', [DeviceController::class, 'register'])
        ->name('api.device.register');
    
    // Cihaz içeriklerini getir
    Route::get('{device_code}/content', [DeviceController::class, 'getContent'])
        ->name('api.device.content');
    
    // Cihaz heartbeat (durum güncelleme)
    Route::post('{device_code}/heartbeat', [DeviceController::class, 'heartbeat'])
        ->name('api.device.heartbeat');
});
