<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'event_date' => 'date',
        'event_start' => 'datetime',
        'event_end' => 'datetime',
        'dp_paid_at' => 'datetime',
    ];

    /**
     * Relasi ke User (Klien)
     * Bisa null jika booking manual (Quick Entry)
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Relasi ke Event yang di-generate dari booking ini
     */
    public function event(): HasOne
    {
        return $this->hasOne(Event::class);
    }

    /**
     * Relasi ke Pembatalan (jika ada)
     */
    public function cancellation(): HasOne
    {
        return $this->hasOne(Cancellation::class);
    }

    /**
     * Relasi ke Feedback Klien
     */
    public function feedback(): HasOne
    {
        return $this->hasOne(ClientFeedback::class);
    }
}
