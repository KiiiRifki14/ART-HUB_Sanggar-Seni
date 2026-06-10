<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name Kostum Jaipong Set A
 * @property string $category jaipong, rampak, degung, topeng
 * @property int $quantity
 * @property string $condition
 * @property string|null $storage_location Lemari A, Rak 2
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $last_cleaned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereLastCleanedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereStorageLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SanggarCostume extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['last_cleaned_at' => 'datetime'];
}
