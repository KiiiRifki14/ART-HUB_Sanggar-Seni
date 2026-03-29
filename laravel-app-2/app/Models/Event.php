<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
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
