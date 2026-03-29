<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialRecord extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_profit_overridden' => 'boolean',
        'budget_warning' => 'boolean',
        'profit_locked' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function operationalCosts(): HasMany
    {
        return $this->hasMany(OperationalCost::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(FinancialAudit::class);
    }
}
