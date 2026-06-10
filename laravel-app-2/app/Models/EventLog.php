<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $event_id
 * @property string $log_type
 * @property string $title Judul singkat kejadian
 * @property string|null $description Detail kejadian
 * @property numeric|null $financial_impact Dampak keuangan jika ada
 * @property int|null $logged_by
 * @property \Illuminate\Support\Carbon $logged_at
 * @property-read \App\Models\Event|null $event
 * @property-read \App\Models\User|null $logger
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereFinancialImpact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereLogType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereLoggedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereLoggedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereTitle($value)
 * @mixin \Eloquent
 */
class EventLog extends Model
{
    public $timestamps = false; // Karena menggunakan logged_at secara manual

    protected $guarded = ['id'];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function logger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
