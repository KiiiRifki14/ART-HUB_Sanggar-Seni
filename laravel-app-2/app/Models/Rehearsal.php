<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
