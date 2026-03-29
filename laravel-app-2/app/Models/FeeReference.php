<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeReference extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function eventPersonnel(): HasMany
    {
        return $this->hasMany(EventPersonnel::class, 'fee_reference_id');
    }
}
