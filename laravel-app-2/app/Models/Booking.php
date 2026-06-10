<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $client_id
 * @property string|null $client_name Nama klien (Quick Entry)
 * @property string|null $client_phone Telp klien (Quick Entry)
 * @property string|null $client_email Email klien (Quick Entry)
 * @property string $event_type jaipong, degung, rampak, dll
 * @property \Illuminate\Support\Carbon $event_date
 * @property \Illuminate\Support\Carbon $event_start
 * @property \Illuminate\Support\Carbon $event_end
 * @property string $venue
 * @property string|null $venue_address
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property numeric $total_price
 * @property numeric|null $price_min
 * @property numeric|null $price_max
 * @property numeric $dp_amount DP 50%
 * @property string|null $payment_receipt Path file bukti transfer
 * @property string|null $full_payment_proof
 * @property string $status
 * @property string $booking_source
 * @property string|null $client_notes
 * @property int|null $service_catalog_id
 * @property string|null $admin_notes
 * @property \Illuminate\Support\Carbon|null $dp_paid_at
 * @property string|null $full_paid_at
 * @property string|null $payment_proof
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Cancellation|null $cancellation
 * @property-read \App\Models\User|null $client
 * @property-read \App\Models\Event|null $event
 * @property-read \App\Models\ClientFeedback|null $feedback
 * @property-read \App\Models\ServiceCatalog|null $serviceCatalog
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereBookingSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereClientEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereClientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereClientNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereClientPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereDpAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereDpPaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereEventEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereEventStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereFullPaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereFullPaymentProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePaymentProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePaymentReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePriceMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePriceMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereServiceCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereVenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereVenueAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking withoutTrashed()
 * @mixin \Eloquent
 */
class Booking extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'event_date' => 'date',
        'event_start' => 'datetime',
        'event_end' => 'datetime',
        'dp_paid_at' => 'datetime',
    ];

    /**
     * Relasi ke ServiceCatalog yang dipilih saat booking
     */
    public function serviceCatalog(): BelongsTo
    {
        return $this->belongsTo(ServiceCatalog::class);
    }

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

    /**
     * Cek apakah DP sudah diverifikasi (ada pembayaran DP)
     */
    public function isDpVerified(): bool
    {
        return !is_null($this->dp_paid_at) && $this->dp_amount > 0;
    }
}
