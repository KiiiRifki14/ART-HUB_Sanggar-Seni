<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $stage_name
 * @property string|null $photo
 * @property string|null $bio
 * @property string $specialty
 * @property bool $has_day_job
 * @property string|null $day_job_desc
 * @property \Illuminate\Support\Carbon|null $day_job_start
 * @property \Illuminate\Support\Carbon|null $day_job_end
 * @property bool $is_active
 * @property string $status
 * @property bool $is_backup Cadangan multi-talent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rehearsal> $rehearsals
 * @property-read int|null $rehearsals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonnelUnavailability> $unavailabilities
 * @property-read int|null $unavailabilities_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereDayJobDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereDayJobEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereDayJobStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereHasDayJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereIsBackup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereSpecialty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereStageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel withoutTrashed()
 * @mixin \Eloquent
 */
class Personnel extends Model
{
    use SoftDeletes;

    protected $table = 'personnel';
    protected $guarded = ['id'];

    protected $casts = [
        'has_day_job' => 'boolean',
        'is_active' => 'boolean',
        'is_backup' => 'boolean',
        'day_job_start' => 'datetime:H:i',
        'day_job_end' => 'datetime:H:i',
        'day_job_days' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_personnel')
            ->withPivot(['role_in_event', 'status', 'fee', 'checked_in_at', 'attendance_status', 'late_minutes'])
            ->withTimestamps();
    }

    // public function schedules(): HasMany
    // {
    //     return $this->hasMany(PersonnelSchedule::class);
    // }

    public function unavailabilities(): HasMany
    {
        return $this->hasMany(PersonnelUnavailability::class);
    }

    public function rehearsals(): BelongsToMany
    {
        return $this->belongsToMany(Rehearsal::class, 'rehearsal_personnel')
            ->withPivot(['checked_in_at', 'attendance_status', 'late_minutes', 'latitude', 'longitude'])
            ->withTimestamps();
    }
}
