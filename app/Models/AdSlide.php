<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdSlide extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'phone_number',
        'icon',
        'media_type',
        'media_path',
        'duration',
        'background_color',
        'is_active',
        'position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration' => 'integer',
        'position' => 'integer',
    ];

    /**
     * Bu reklam slaytına atanmış cihazlar
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'device_ad_slide')
            ->withPivot('order', 'is_active')
            ->withTimestamps();
    }
}
