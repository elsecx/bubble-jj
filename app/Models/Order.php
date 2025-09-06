<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $guarded = [
        'id',
    ];

    protected function statusColor(): Attribute
    {
        return Attribute::get(fn($value, $attributes) => match ($attributes['status']) {
            'pending'  => 'warning',
            'rejected' => 'danger',
            'approved' => 'success',
            default    => 'secondary',
        });
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(
            fn($value, $attributes) => match ($attributes['status']) {
                'pending'  => 'Menunggu',
                'rejected' => 'Ditolak',
                'approved' => 'Disetujui',
                default    => ucfirst($attributes['status']),
            }
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(UploadCategory::class, 'category_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
