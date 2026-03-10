<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'header_logo',
        'header_button_text',
        'header_phone',
        'social_links',
        'hero_image',
        'hero_title',
        'hero_subtitle',
        'statistics',
    ];

    protected $casts = [
        'social_links' => 'array',
        'statistics' => 'array',
    ];

    protected static function booted()
    {
        static::updating(function ($setting) {
            if($setting->isDirty('hero_image') && $setting->getOriginal('hero_image')) {
                Storage::disk('public')->delete($setting->getOriginal('hero_image'));
            }
            if($setting->isDirty('header_logo') && $setting->getOriginal('header_logo')) {
                Storage::disk('public')->delete($setting->getOriginal('header_logo'));
            }
        });
    }
}
