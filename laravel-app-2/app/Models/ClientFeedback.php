<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientFeedback extends Model
{
    // The table does not have created_at/updated_at by default, only submitted_at
    // But since Laravel's Model assumes it by default, we'll turn it off and handle submitted_at
    public $timestamps = false;
    protected $table = 'client_feedbacks';

    protected $fillable = [
        'booking_id',
        'rating',
        'testimony',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * Relasi kembali ke Booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
