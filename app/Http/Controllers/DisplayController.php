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
     * Cihaz kodu zorunlu - ?device=DEVICE_CODE parametresi ile çalışır
     */
    public function index(Request $request)
    {
        $deviceCode = $request->query('device');
        
        // Cihaz kodu yoksa hata sayfası göster
        if (!$deviceCode) {
            return response()->view('display.error', [
                'title' => 'Cihaz Kodu Gerekli',
                'message' => 'Lütfen URL\'ye cihaz kodu ekleyin: /display?device=CIHAZ_KODU',
                'code' => 'NO_DEVICE_CODE'
            ], 400);
        }
        
        // Cihazı bul
        $device = Device::where('device_code', $deviceCode)->first();
        
        // Cihaz bulunamadıysa otomatik kaydet
        if (!$device) {
            $device = Device::create([
                'device_code' => $deviceCode,
                'name' => 'Yeni Cihaz - ' . $deviceCode,
                'location' => 'Belirlenmedi',
                'status' => 'active',
                'ip_address' => $request->ip(),
                'last_sync_at' => now(),
            ]);
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
            'ip_address' => $request->ip(),
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
     * Cihaz kodu zorunlu - ?device=DEVICE_CODE parametresi ile çalışır
     */
    public function api(Request $request)
    {
        $deviceCode = $request->query('device');
        
        // Cihaz kodu yoksa hata döndür
        if (!$deviceCode) {
            return response()->json([
                'success' => false,
                'message' => 'Cihaz kodu gerekli: ?device=CIHAZ_KODU',
                'code' => 'NO_DEVICE_CODE'
            ], 400);
        }
        
        // Cihazı bul
        $device = Device::where('device_code', $deviceCode)->first();
        
        if (!$device) {
            return response()->json([
                'success' => false,
                'hash' => null,
                'device_code' => $deviceCode,
                'status' => 'not_found',
                'message' => 'Cihaz bulunamadı'
            ], 404);
        }
        
        if ($device->status !== 'active') {
            return response()->json([
                'success' => false,
                'hash' => null,
                'device_code' => $deviceCode,
                'status' => $device->status,
                'message' => 'Cihaz aktif değil'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'hash' => $this->calculateDeviceDataHash($device),
            'device_code' => $deviceCode,
            'device_name' => $device->name,
            'status' => 'active',
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
