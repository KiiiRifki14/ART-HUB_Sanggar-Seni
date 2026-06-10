<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $event_id
 * @property string $type 3-Stage Rehearsal
 * @property \Illuminate\Support\Carbon $rehearsal_date
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property string|null $location
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Personnel> $personnel
 * @property-read int|null $personnel_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereRehearsalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Rehearsal extends Model
{
    protected $guarded = ['id'];



    protected $casts = [
        'rehearsal_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function personnel()
    {
        return $this->belongsToMany(Personnel::class, 'rehearsal_personnel')
                    ->withPivot(['checked_in_at', 'attendance_status', 'late_minutes', 'latitude', 'longitude'])
                    ->withTimestamps();
    }
}
