<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MugenSubmission extends Model
{
    protected $guarded = [];
    protected $casts = ['answers' => 'array'];
}
