<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $guarded = [
        'id',
    ];

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function category(): BelongsTo
    {
        return $this->belongsTo(UploadCategory::class, 'category_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
