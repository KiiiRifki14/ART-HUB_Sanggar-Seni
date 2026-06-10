<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $personnel_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Personnel|null $personnel
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability wherePersonnelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PersonnelUnavailability extends Model
{
    protected $table = 'personnel_unavailabilities';
    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }
}
