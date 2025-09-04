<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class UploadCategory extends Model
{
    use Cacheable;

    protected $guarded = ['id'];
    public $timestamps = false;

    protected static function booted()
    {
        static::saved(function ($menu) {
            Cache::forget('menus_all');
        });

        static::deleted(function ($menu) {
            Cache::forget('menus_all');
        });
    }
}
