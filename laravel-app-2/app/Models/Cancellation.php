<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $booking_id
 * @property \Illuminate\Support\Carbon $cancellation_date
 * @property int $days_before_event
 * @property numeric $penalty_percentage
 * @property numeric $penalty_amount
 * @property numeric $refund_amount
 * @property string $status
 * @property string|null $reason
 * @property bool $digital_acknowledgement Tanda tangan digital kebijakan
 * @property string|null $acknowledged_ip IP address klien saat menyetujui digital acknowledgement
 * @property string|null $acknowledged_at Timestamp saat klien menyetujui digital acknowledgement
 * @property string|null $acknowledged_ua User agent browser klien saat menyetujui digital acknowledgement
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking|null $booking
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereAcknowledgedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereAcknowledgedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereAcknowledgedUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereCancellationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereDaysBeforeEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereDigitalAcknowledgement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation wherePenaltyAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation wherePenaltyPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
