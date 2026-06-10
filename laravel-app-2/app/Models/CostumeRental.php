<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $event_id
 * @property int $vendor_id
 * @property string $costume_type
 * @property int $quantity
 * @property \Illuminate\Support\Carbon $rental_date
 * @property \Illuminate\Support\Carbon $due_date
 * @property \Illuminate\Support\Carbon|null $returned_date
 * @property string $status
 * @property numeric $rental_cost
 * @property numeric $overdue_fine Denda kumulatif
 * @property int $overdue_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @property-read \App\Models\CostumeVendor $vendor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereCostumeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereOverdueDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereOverdueFine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereRentalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereRentalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereReturnedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereVendorId($value)
 * @mixin \Eloquent
 */
class CostumeRental extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['rental_date' => 'date', 'due_date' => 'date', 'returned_date' => 'date'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function vendor()
    {
        return $this->belongsTo(CostumeVendor::class, 'vendor_id');
    }
}
