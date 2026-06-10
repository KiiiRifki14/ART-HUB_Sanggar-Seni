<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $financial_record_id
 * @property string $field_changed
 * @property numeric $old_value
 * @property numeric $new_value
 * @property int|null $changed_by
 * @property string|null $change_reason
 * @property \Illuminate\Support\Carbon $changed_at
 * @property-read \App\Models\FinancialRecord $financialRecord
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit whereChangeReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit whereChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit whereChangedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit whereFieldChanged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit whereFinancialRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit whereNewValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FinancialAudit whereOldValue($value)
 * @mixin \Eloquent
 */
class FinancialAudit extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $casts = ['changed_at' => 'datetime'];

    public function financialRecord(): BelongsTo
    {
        return $this->belongsTo(FinancialRecord::class);
    }
}
