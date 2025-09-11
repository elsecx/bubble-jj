<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use Cacheable;

    protected $guarded = ['id'];

    public $timestamps = false;
}
