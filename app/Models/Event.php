<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'description',
        'category',
        'image_path',
    ];

    protected static function booted()
    {
        static::deleting(function ($event) {
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('image_path')) {
                $originalImage = $event->getOriginal('image_path');
                if ($originalImage) {
                    Storage::disk('public')->delete($originalImage);
                }
            }
        });
    }
}
