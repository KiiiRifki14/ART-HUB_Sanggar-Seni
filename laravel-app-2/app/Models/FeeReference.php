<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $role_name Penari Utama, Pemusik, Penari Latar, Cadangan
 * @property numeric $base_fee Tarif dasar per event (Rupiah)
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereBaseFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereRoleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FeeReference extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
