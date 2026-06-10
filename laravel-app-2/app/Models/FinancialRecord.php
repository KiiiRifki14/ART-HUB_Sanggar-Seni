<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $event_id
 * @property numeric $total_revenue Total harga event
 * @property numeric $fixed_profit_pct Persentase default 30%
 * @property bool $is_profit_overridden Apakah di-override manual
 * @property numeric $fixed_profit Nominal laba tetap pimpinan
 * @property numeric $dp_received
 * @property numeric $total_personnel_honor Total honor semua personel
 * @property numeric $operational_budget Anggaran dari sisa DP
 * @property numeric $actual_operational_cost Realisasi biaya
 * @property numeric $net_profit Laba bersih akhir
 * @property numeric $safety_buffer_pct 10% buffer dari anggaran
 * @property numeric $safety_buffer_amt
 * @property bool $budget_warning
 * @property string|null $warning_message
 * @property bool $profit_locked DP masuk = profit terkunci
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FinancialAudit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Event|null $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OperationalCost> $operationalCosts
 * @property-read int|null $operational_costs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereActualOperationalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereBudgetWarning($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereDpReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereFixedProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereFixedProfitPct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereIsProfitOverridden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereNetProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereOperationalBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereProfitLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereSafetyBufferAmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereSafetyBufferPct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereTotalPersonnelHonor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereTotalRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialRecord whereWarningMessage($value)
 * @mixin \Eloquent
 */
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
