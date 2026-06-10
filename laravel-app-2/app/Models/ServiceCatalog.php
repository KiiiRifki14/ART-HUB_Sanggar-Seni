<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $detail
 * @property int $price
 * @property string|null $image
 * @property string|null $badge
 * @property bool $is_active
 * @property int $sort_order
 * @property int $max_personnel 0 = tidak ada batas. > 0 = maks personel yang bisa di-plot
 * @property string $specialty_type Jenis personel yang dibutuhkan katalog ini
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read float $average_rating
 * @property-read string $price_formatted
 * @property-read string $specialty_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereBadge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereMaxPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereSpecialtyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServiceCatalog extends Model
{
    protected $fillable = [
        'name',
        'description',
        'detail',
        'price',
        'image',
        'badge',
        'is_active',
        'sort_order',
        'max_personnel',
        'specialty_type',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'price'          => 'integer',
        'sort_order'     => 'integer',
        'max_personnel'  => 'integer',
    ];

    /**
     * Format harga ke rupiah.
     */
    public function getPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Label specialty untuk UI.
     */
    public function getSpecialtyLabelAttribute(): string
    {
        return match($this->specialty_type) {
            'penari'  => 'Penari',
            'pemusik' => 'Pemusik',
            default   => 'Penari + Pemusik',
        };
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Hitung rata-rata rating dari feedback klien
     */
    public function getAverageRatingAttribute(): float
    {
        $avg = \App\Models\ClientFeedback::whereHas('booking', function ($q) {
            $q->where('service_catalog_id', $this->id);
        })->avg('rating');

        return $avg ? round((float) $avg, 1) : 0.0;
    }
}
