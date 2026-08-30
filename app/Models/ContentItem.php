<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentItem extends Model
{
    protected $guarded = [];
    protected $casts = ['metadata' => 'array', 'is_active' => 'boolean'];
}
