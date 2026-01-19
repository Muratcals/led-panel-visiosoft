<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Device extends Model
{
    protected $fillable = [
        'name',
        'device_code',
        'location',
        'ip_address',
        'status',
        'last_sync_at',
        'settings',
        'notes',
    ];

    protected $casts = [
        'settings' => 'array',
        'last_sync_at' => 'datetime',
    ];

    /**
     * Cihaza atanmış videolar
     */
    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'device_video')
            ->withPivot('order', 'is_active')
            ->withTimestamps()
            ->orderBy('order');
    }

    /**
     * Cihaza atanmış reklam slayları
     */
    public function adSlides(): BelongsToMany
    {
        return $this->belongsToMany(AdSlide::class, 'device_ad_slide')
            ->withPivot('order', 'is_active')
            ->withTimestamps()
            ->orderBy('order');
    }

    /**
     * Cihaza atanmış fiyat tarifeleri
     */
    public function priceTariffs(): BelongsToMany
    {
        return $this->belongsToMany(PriceTariff::class, 'device_price_tariff')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    /**
     * Cihaz aktif mi?
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Cihazın son senkronizasyonu ne zaman?
     */
    public function getLastSyncHumanAttribute(): string
    {
        return $this->last_sync_at?->diffForHumans() ?? 'Hiç senkronize olmadı';
    }
}
