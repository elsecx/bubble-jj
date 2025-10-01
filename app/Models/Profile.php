<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = ['id'];

    public function setUsername1Attribute($value)
    {
        $this->attributes['username_1'] = sanitizeUsername($value);
    }

    public function setUsername2Attribute($value)
    {
        $this->attributes['username_2'] = sanitizeUsername($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
