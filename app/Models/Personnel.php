<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Personnel extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'has_day_job' => 'boolean',
        'is_active' => 'boolean',
        'is_backup' => 'boolean',
        'day_job_start' => 'datetime:H:i',
        'day_job_end' => 'datetime:H:i',
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

    public function schedules(): HasMany
    {
        return $this->hasMany(PersonnelSchedule::class);
    }
}
