<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int|null $client_id
 * @property string|null $client_name Nama klien (Quick Entry)
 * @property string|null $client_phone Telp klien (Quick Entry)
 * @property string|null $client_email Email klien (Quick Entry)
 * @property string $event_type jaipong, degung, rampak, dll
 * @property \Illuminate\Support\Carbon $event_date
 * @property \Illuminate\Support\Carbon $event_start
 * @property \Illuminate\Support\Carbon $event_end
 * @property string $venue
 * @property string|null $venue_address
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property numeric $total_price
 * @property numeric|null $price_min
 * @property numeric|null $price_max
 * @property numeric $dp_amount DP 50%
 * @property string|null $payment_receipt Path file bukti transfer
 * @property string|null $full_payment_proof
 * @property string $status
 * @property string $booking_source
 * @property string|null $client_notes
 * @property int|null $service_catalog_id
 * @property string|null $admin_notes
 * @property \Illuminate\Support\Carbon|null $dp_paid_at
 * @property string|null $full_paid_at
 * @property string|null $payment_proof
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Cancellation|null $cancellation
 * @property-read \App\Models\User|null $client
 * @property-read \App\Models\Event|null $event
 * @property-read \App\Models\ClientFeedback|null $feedback
 * @property-read \App\Models\ServiceCatalog|null $serviceCatalog
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereBookingSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereClientEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereClientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereClientNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereClientPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereDpAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereDpPaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereEventEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereEventStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereFullPaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereFullPaymentProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePaymentProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePaymentReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePriceMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePriceMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereServiceCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereVenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereVenueAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking withoutTrashed()
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $booking_id
 * @property \Illuminate\Support\Carbon $cancellation_date
 * @property int $days_before_event
 * @property numeric $penalty_percentage
 * @property numeric $penalty_amount
 * @property numeric $refund_amount
 * @property string $status
 * @property string|null $reason
 * @property bool $digital_acknowledgement Tanda tangan digital kebijakan
 * @property string|null $acknowledged_ip IP address klien saat menyetujui digital acknowledgement
 * @property string|null $acknowledged_at Timestamp saat klien menyetujui digital acknowledgement
 * @property string|null $acknowledged_ua User agent browser klien saat menyetujui digital acknowledgement
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking|null $booking
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereAcknowledgedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereAcknowledgedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereAcknowledgedUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereCancellationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereDaysBeforeEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereDigitalAcknowledgement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation wherePenaltyAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation wherePenaltyPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancellation whereUpdatedAt($value)
 */
	class Cancellation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $booking_id
 * @property int $rating 1 sampai 5 bintang
 * @property string|null $testimony
 * @property \Illuminate\Support\Carbon $submitted_at
 * @property-read \App\Models\Booking|null $booking
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClientFeedback whereTestimony($value)
 */
	class ClientFeedback extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property int $vendor_id
 * @property string $costume_type
 * @property int $quantity
 * @property \Illuminate\Support\Carbon $rental_date
 * @property \Illuminate\Support\Carbon $due_date
 * @property \Illuminate\Support\Carbon|null $returned_date
 * @property string $status
 * @property numeric $rental_cost
 * @property numeric $overdue_fine Denda kumulatif
 * @property int $overdue_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @property-read \App\Models\CostumeVendor $vendor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereCostumeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereOverdueDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereOverdueFine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereRentalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereRentalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereReturnedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeRental whereVendorId($value)
 */
	class CostumeRental extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property int $costume_id
 * @property int $quantity_used
 * @property \Illuminate\Support\Carbon $checkout_date
 * @property \Illuminate\Support\Carbon $expected_return_date
 * @property \Illuminate\Support\Carbon|null $actual_return_date
 * @property string $status
 * @property string|null $damage_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereActualReturnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereCheckoutDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereCostumeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereDamageNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereExpectedReturnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereQuantityUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeUsage whereUpdatedAt($value)
 */
	class CostumeUsage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $city Subang, Bandung
 * @property string|null $phone
 * @property string|null $address
 * @property int $return_deadline_days Batas hari pengembalian
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereReturnDeadlineDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CostumeVendor whereUpdatedAt($value)
 */
	class CostumeVendor extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
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
 */
	class EventLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $role_name Penari Utama, Pemusik, Penari Latar, Cadangan
 * @property numeric $base_fee Tarif dasar per event (Rupiah)
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereBaseFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereRoleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeReference whereUpdatedAt($value)
 */
	class FeeReference extends \Eloquent {}
}

namespace App\Models{
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
 */
	class FinancialAudit extends \Eloquent {}
}

namespace App\Models{
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
 */
	class FinancialRecord extends \Eloquent {}
}

namespace App\Models{
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
 */
	class OperationalCost extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Personnel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $personnel_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Personnel|null $personnel
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability wherePersonnelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelUnavailability whereUpdatedAt($value)
 */
	class PersonnelUnavailability extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string $type 3-Stage Rehearsal
 * @property \Illuminate\Support\Carbon $rehearsal_date
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property string|null $location
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Personnel> $personnel
 * @property-read int|null $personnel_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereRehearsalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rehearsal whereUpdatedAt($value)
 */
	class Rehearsal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name Kostum Jaipong Set A
 * @property string $category jaipong, rampak, degung, topeng
 * @property int $quantity
 * @property string $condition
 * @property string|null $storage_location Lemari A, Rak 2
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $last_cleaned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereLastCleanedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereStorageLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SanggarCostume whereUpdatedAt($value)
 */
	class SanggarCostume extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $detail
 * @property int $price
 * @property string|null $image
 * @property string|null $badge
 * @property bool $is_active
 * @property int $sort_order
 * @property int $max_personnel 0 = tidak ada batas. > 0 = maks personel yang bisa di-plot
 * @property string $specialty_type Jenis personel yang dibutuhkan katalog ini
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read float $average_rating
 * @property-read string $price_formatted
 * @property-read string $specialty_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereBadge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereMaxPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereSpecialtyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceCatalog whereUpdatedAt($value)
 */
	class ServiceCatalog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteContent whereValue($value)
 */
	class SiteContent extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $otp_code
 * @property string|null $otp_expires_at
 * @property string $role
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Personnel|null $personnelProfile
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

