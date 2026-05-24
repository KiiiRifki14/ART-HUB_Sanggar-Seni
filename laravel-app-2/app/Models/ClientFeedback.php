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
     * FIX F-02: Pastikan submitted_at otomatis terisi saat model dibuat
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->submitted_at)) {
                $model->submitted_at = now();
            }
        });
    }

    /**
     * Relasi kembali ke Booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
