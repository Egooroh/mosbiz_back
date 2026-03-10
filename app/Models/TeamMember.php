<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'description',
        'photo_path',
        'is_head',
    ];

    protected static function booted()
    {
        static::deleting(function ($member) {
            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }
        });

        static::updating(function ($member) {
            if ($member->isDirty('photo_path')) {
                $originalPhoto = $member->getOriginal('photo_path');
                if ($originalPhoto) {
                    Storage::disk('public')->delete($originalPhoto);
                }
            }
        });
    }
}
