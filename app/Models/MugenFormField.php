<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MugenFormField extends Model
{
    protected $guarded = [];
    protected $casts = ['options' => 'array', 'is_required' => 'boolean', 'is_active' => 'boolean'];
}
