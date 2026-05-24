<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
