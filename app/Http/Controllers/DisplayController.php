<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\PriceTariff;
use App\Models\AdSlide;
use App\Models\Setting;
use App\Models\Device;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    /**
     * LED Panel ana görünümü
     * Cihaz kodu opsiyonel - IP adresine göre otomatik tanıma yapılır
     */
    public function index(Request $request)
    {
        $deviceCode = $request->query('device');
        $clientIp = $request->ip();
        
        // Önce cihaz koduna göre ara
        if ($deviceCode) {
            $device = Device::where('device_code', $deviceCode)->first();
            
            // Cihaz bulunamadıysa otomatik kaydet
            if (!$device) {
                $device = Device::create([
                    'device_code' => $deviceCode,
                    'name' => 'Yeni Cihaz - ' . $deviceCode,
                    'location' => 'Belirlenmedi',
                    'status' => 'active',
                    'ip_address' => $clientIp,
                    'last_sync_at' => now(),
                ]);
            }
        } else {
            // Cihaz kodu yoksa IP adresine göre bul veya oluştur
            $device = Device::where('ip_address', $clientIp)->first();
            
            if (!$device) {
                // IP ile kayıtlı cihaz yok, otomatik oluştur
                $deviceCode = 'AUTO-' . strtoupper(substr(md5($clientIp), 0, 8));
                $device = Device::create([
                    'device_code' => $deviceCode,
                    'name' => 'Otomatik Cihaz - ' . $clientIp,
                    'location' => 'Otomatik Kayıt',
                    'status' => 'active',
                    'ip_address' => $clientIp,
                    'last_sync_at' => now(),
                ]);
            }
            
            $deviceCode = $device->device_code;
        }
        
        // Cihaz aktif değilse uyarı göster
        if ($device->status !== 'active') {
            return response()->view('display.error', [
                'title' => 'Cihaz Aktif Değil',
                'message' => 'Bu cihaz şu anda ' . ($device->status === 'maintenance' ? 'bakımda' : 'pasif') . ' durumunda.',
                'code' => 'DEVICE_INACTIVE',
                'device' => $device
            ], 403);
        }
        
        // Cihazın son erişim zamanını güncelle
        $device->update([
            'last_sync_at' => now(),
            'ip_address' => $clientIp,
        ]);
        
        // Cihaza atanmış videoları getir
        $topVideos = $device->videos()
            ->where('position', 'top')
            ->wherePivot('is_active', true)
            ->orderBy('device_video.order')
            ->get();

        $bottomVideos = $device->videos()
            ->where('position', 'bottom')
            ->wherePivot('is_active', true)
            ->orderBy('device_video.order')
            ->get();

        // Cihaza atanmış fiyat tarifelerini getir
        $priceTariffs = $device->priceTariffs()
            ->wherePivot('is_active', true)
            ->orderBy('order')
            ->get();

        // Cihaza atanmış reklam slaytlarını getir
        $adSlides = $device->adSlides()
            ->wherePivot('is_active', true)
            ->orderBy('device_ad_slide.order')
            ->get();

        // Cihaz ayarlarını kullan, yoksa genel ayarları
        $settings = !empty($device->settings) 
            ? collect($device->settings) 
            : Setting::pluck('value', 'key');

        $dataHash = $this->calculateDeviceDataHash($device);

        return view('display.index', compact(
            'topVideos',
            'bottomVideos',
            'priceTariffs',
            'adSlides',
            'settings',
            'dataHash',
            'device',
            'deviceCode'
        ));
    }

    /**
     * API endpoint - Cihaz hash kontrolü için
     * Cihaz kodu opsiyonel - IP adresine göre otomatik tanıma yapılır
     */
    public function api(Request $request)
    {
        $deviceCode = $request->query('device');
        $clientIp = $request->ip();
        
        // Cihaz koduna göre ara
        if ($deviceCode) {
            $device = Device::where('device_code', $deviceCode)->first();
        } else {
            // Cihaz kodu yoksa IP adresine göre bul
            $device = Device::where('ip_address', $clientIp)->first();
        }
        
        // Cihaz bulunamadıysa yeni oluştur
        if (!$device) {
            if (!$deviceCode) {
                $deviceCode = 'AUTO-' . strtoupper(substr(md5($clientIp), 0, 8));
            }
            
            $device = Device::create([
                'device_code' => $deviceCode,
                'name' => 'Otomatik Cihaz - ' . $clientIp,
                'location' => 'Otomatik Kayıt',
                'status' => 'active',
                'ip_address' => $clientIp,
                'last_sync_at' => now(),
            ]);
        }
        
        if ($device->status !== 'active') {
            return response()->json([
                'success' => false,
                'hash' => null,
                'device_code' => $device->device_code,
                'status' => $device->status,
                'message' => 'Cihaz aktif değil'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'hash' => $this->calculateDeviceDataHash($device),
            'device_code' => $device->device_code,
            'device_name' => $device->name,
            'status' => 'active',
            'last_update' => now()->toIso8601String(),
        ]);
    }

    /**
     * Genel veri hash hesabı
     */
    private function calculateDataHash()
    {
        $lastVideoUpdate = Video::max('updated_at');
        $lastTariffUpdate = PriceTariff::max('updated_at');
        $lastSlideUpdate = AdSlide::max('updated_at');
        $lastSettingUpdate = Setting::max('updated_at');

        return md5($lastVideoUpdate . $lastTariffUpdate . $lastSlideUpdate . $lastSettingUpdate);
    }

    /**
     * Cihaz bazlı veri hash hesabı
     */
    private function calculateDeviceDataHash(Device $device)
    {
        $lastVideoUpdate = $device->videos()->max('videos.updated_at');
        $lastSlideUpdate = $device->adSlides()->max('ad_slides.updated_at');
        $lastTariffUpdate = $device->priceTariffs()->max('price_tariffs.updated_at');
        $deviceUpdate = $device->updated_at;
        
        // Pivot tablolarındaki değişiklikleri de dahil et
        $pivotVideoUpdate = $device->videos()->max('device_video.updated_at');
        $pivotSlideUpdate = $device->adSlides()->max('device_ad_slide.updated_at');

        return md5($lastVideoUpdate . $lastSlideUpdate . $lastTariffUpdate . $deviceUpdate . $pivotVideoUpdate . $pivotSlideUpdate);
    }
}
