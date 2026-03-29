<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanggarCostume extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['last_cleaned_at' => 'datetime'];
}
