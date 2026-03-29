<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cancellation extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'cancellation_date' => 'date',
        'digital_acknowledgement' => 'boolean',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
