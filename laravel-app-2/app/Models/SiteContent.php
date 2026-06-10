<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent whereValue($value)
 * @mixin \Eloquent
 */
class SiteContent extends Model
{
    protected $guarded = ['id'];
}
