<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostumeUsage extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'checkout_date' => 'date', 
        'expected_return_date' => 'date', 
        'actual_return_date' => 'date'
    ];
}
