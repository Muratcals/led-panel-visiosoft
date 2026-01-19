<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Cihaz için içerikleri getir
     * GET /api/device/{device_code}/content
     */
    public function getContent(string $deviceCode)
    {
        $device = Device::where('device_code', $deviceCode)
            ->where('status', 'active')
            ->with([
                'videos' => fn($q) => $q->where('is_active', true)->orderBy('order'),
                'adSlides' => fn($q) => $q->where('is_active', true)->orderBy('order'),
                'priceTariffs' => fn($q) => $q->where('is_active', true)->orderBy('order'),
            ])
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Cihaz bulunamadı veya aktif değil'
            ], 404);
        }

        // Son senkronizasyon zamanını güncelle
        $device->update(['last_sync_at' => now()]);

        return response()->json([
            'success' => true,
            'device' => [
                'code' => $device->device_code,
                'name' => $device->name,
                'location' => $device->location,
                'last_sync' => $device->last_sync_at,
            ],
            'content' => [
                'videos' => $device->videos->map(fn($video) => [
                    'id' => $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'file_url' => asset('storage/' . $video->file_path),
                    'duration' => $video->duration,
                    'position' => $video->position,
                    'order' => $video->pivot->order,
                ]),
                'ad_slides' => $device->adSlides->map(fn($slide) => [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'subtitle' => $slide->subtitle,
                    'phone_number' => $slide->phone_number,
                    'icon' => $slide->icon,
                    'media_type' => $slide->media_type,
                    'media_url' => $slide->media_path ? asset('storage/' . $slide->media_path) : null,
                    'duration' => $slide->duration,
                    'background_color' => $slide->background_color,
                    'position' => $slide->position,
                    'order' => $slide->pivot->order,
                ]),
                'price_tariffs' => $device->priceTariffs->map(fn($tariff) => [
                    'id' => $tariff->id,
                    'time_range' => $tariff->time_range,
                    'price' => $tariff->price,
                    'is_free' => $tariff->is_free,
                    'is_highlighted' => $tariff->is_highlighted,
                    'order' => $tariff->order,
                ]),
            ],
            'settings' => $device->settings ?? [],
        ]);
    }

    /**
     * Cihaz durumunu güncelle
     * POST /api/device/{device_code}/heartbeat
     */
    public function heartbeat(Request $request, string $deviceCode)
    {
        $device = Device::where('device_code', $deviceCode)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Cihaz bulunamadı'
            ], 404);
        }

        $device->update([
            'last_sync_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat kaydedildi',
            'device_status' => $device->status,
        ]);
    }

    /**
     * Cihaz otomatik kayıt
     * POST /api/device/register
     */
    public function register(Request $request)
    {
        $request->validate([
            'device_code' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $deviceCode = $request->input('device_code');
        
        // Mevcut cihazı bul veya yeni oluştur
        $device = Device::firstOrCreate(
            ['device_code' => $deviceCode],
            [
                'name' => $request->input('name', 'Yeni Cihaz - ' . $deviceCode),
                'location' => $request->input('location', 'Belirlenmedi'),
                'status' => 'active',
                'ip_address' => $request->ip(),
                'last_sync_at' => now(),
            ]
        );

        // IP adresini ve son erişimi güncelle
        $device->update([
            'ip_address' => $request->ip(),
            'last_sync_at' => now(),
        ]);

        $isNew = $device->wasRecentlyCreated;

        return response()->json([
            'success' => true,
            'message' => $isNew ? 'Cihaz başarıyla kaydedildi' : 'Cihaz zaten kayıtlı',
            'is_new' => $isNew,
            'device' => [
                'code' => $device->device_code,
                'name' => $device->name,
                'location' => $device->location,
                'status' => $device->status,
                'ip_address' => $device->ip_address,
            ],
        ]);
    }
}
