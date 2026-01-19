<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PriceTariff extends Model
{
    protected $fillable = [
        'time_range',
        'price',
        'order',
        'is_free',
        'is_highlighted',
        'is_active',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_highlighted' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'order' => 'integer',
    ];

    /**
     * Bu fiyat tarifesine atanmış cihazlar
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'device_price_tariff')
            ->withPivot('is_active')
            ->withTimestamps();
    }
}
