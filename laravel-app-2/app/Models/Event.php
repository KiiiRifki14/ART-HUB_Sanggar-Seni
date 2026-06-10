<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $booking_id
 * @property string $event_code EVT-2026-001
 * @property string $status
 * @property \Illuminate\Support\Carbon $event_date
 * @property \Illuminate\Support\Carbon $event_start
 * @property \Illuminate\Support\Carbon $event_end
 * @property string $venue
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property-read int|null $personnel_count 11 inti + 1 cadangan
 * @property numeric $estimated_total_honor Auto dari fee_references
 * @property string|null $admin_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Booking|null $booking
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventLog> $eventLogs
 * @property-read int|null $event_logs_count
 * @property-read \App\Models\FinancialRecord|null $financialRecord
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Personnel> $personnel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rehearsal> $rehearsals
 * @property-read int|null $rehearsals_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEstimatedTotalHonor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePersonnelCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereVenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withoutTrashed()
 * @mixin \Eloquent
 */
class Event extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'event_date' => 'date',
        'event_start' => 'datetime',
        'event_end' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function financialRecord(): HasOne
    {
        return $this->hasOne(FinancialRecord::class);
    }

    public function personnel(): BelongsToMany
    {
        return $this->belongsToMany(Personnel::class, 'event_personnel')
                    ->withPivot(['role_in_event', 'status', 'fee', 'checked_in_at', 'attendance_status', 'late_minutes'])
                    ->withTimestamps();
    }

    public function eventLogs(): HasMany
    {
        return $this->hasMany(EventLog::class);
    }

    public function rehearsals(): HasMany
    {
        return $this->hasMany(Rehearsal::class);
    }
}
