<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $financial_record_id
 * @property string $category bensin, konsumsi, parkir, honor, denda_insiden, sewa_kostum, lainnya
 * @property string|null $description
 * @property numeric $estimated_amount
 * @property numeric $actual_amount
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FinancialRecord $financialRecord
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost whereActualAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost whereEstimatedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost whereFinancialRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OperationalCost whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class OperationalCost extends Model
{
    protected $guarded = ['id'];

    public function financialRecord(): BelongsTo
    {
        return $this->belongsTo(FinancialRecord::class);
    }
}
