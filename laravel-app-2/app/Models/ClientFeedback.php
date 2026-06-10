<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $booking_id
 * @property int $rating 1 sampai 5 bintang
 * @property string|null $testimony
 * @property \Illuminate\Support\Carbon $submitted_at
 * @property-read \App\Models\Booking|null $booking
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback whereTestimony($value)
 * @mixin \Eloquent
 */
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
