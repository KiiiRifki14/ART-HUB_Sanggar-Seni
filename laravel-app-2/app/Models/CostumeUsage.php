<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $event_id
 * @property int $costume_id
 * @property int $quantity_used
 * @property \Illuminate\Support\Carbon $checkout_date
 * @property \Illuminate\Support\Carbon $expected_return_date
 * @property \Illuminate\Support\Carbon|null $actual_return_date
 * @property string $status
 * @property string|null $damage_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereActualReturnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereCheckoutDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereCostumeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereDamageNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereExpectedReturnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereQuantityUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CostumeUsage extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'checkout_date' => 'date', 
        'expected_return_date' => 'date', 
        'actual_return_date' => 'date'
    ];
}
