<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $city Subang, Bandung
 * @property string|null $phone
 * @property string|null $address
 * @property int $return_deadline_days Batas hari pengembalian
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereReturnDeadlineDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CostumeVendor extends Model
{
    protected $guarded = ['id'];
}
