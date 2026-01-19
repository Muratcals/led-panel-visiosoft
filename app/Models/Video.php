<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Video extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'duration',
        'order',
        'is_active',
        'position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Bu videoya atanmış cihazlar
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'device_video')
            ->withPivot('order', 'is_active')
            ->withTimestamps();
    }
}
