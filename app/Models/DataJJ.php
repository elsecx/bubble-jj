<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataJJ extends Model
{
    protected $table = 'data_jj';

    protected $guarded = [
        'id',
    ];

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
