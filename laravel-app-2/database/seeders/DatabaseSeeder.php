<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Personnel;
use App\Models\FeeReference;
use App\Models\Booking;
use App\Models\Event;
use App\Models\FinancialRecord;
use App\Models\OperationalCost;
use App\Models\CostumeVendor;
use App\Models\CostumeRental;
use App\Models\SanggarCostume;
use App\Models\CostumeUsage;
use App\Models\Cancellation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════════════
        // 1. USERS (3 Role: Admin, Personel, Klien)
        // ═══════════════════════════════════════════════════
        $admin = User::create([
            'name' => 'Pak Yatno (Admin)',
            'email' => 'pakyat@arthub.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 12 Personel Users
        $personnelData = [
            ['name' => 'Sinta Nurhaliza',   'specialty' => 'penari',       'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Dewi Anggraeni',    'specialty' => 'penari',       'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Rina Kartika',      'specialty' => 'penari',       'has_day_job' => true,  'day_job_desc' => 'PNS Dinas Pendidikan', 'is_backup' => false],
            ['name' => 'Neng Kokom',        'specialty' => 'penari',       'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Yuli Rahayu',       'specialty' => 'penari',       'has_day_job' => true,  'day_job_desc' => 'Guru SD Negeri', 'is_backup' => false],
            ['name' => 'Asep Sunandar',     'specialty' => 'pemusik',      'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Dadan Supardan',    'specialty' => 'pemusik',      'has_day_job' => true,  'day_job_desc' => 'Pegawai Kecamatan', 'is_backup' => false],
            ['name' => 'Ujang Kurniawan',   'specialty' => 'pemusik',      'has_day_job' => true,  'day_job_desc' => 'Satpam Bank BRI', 'is_backup' => false],
            ['name' => 'Iis Sugianti',      'specialty' => 'penari',       'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Lilis Suryani',     'specialty' => 'penari',       'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Bambang Pamungkas', 'specialty' => 'multi_talent', 'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Cecep Hidayat',     'specialty' => 'multi_talent', 'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => true],
        ];

        $personnelRecords = [];
        foreach ($personnelData as $i => $p) {
            $user = User::create([
                'name' => $p['name'],
                'email' => strtolower(str_replace([' ', '.'], ['_', ''], $p['name'])) . '@arthub.local',
                'password' => Hash::make('password'),
                'role' => 'personel',
            ]);
            $personnelRecords[] = Personnel::create([
                'user_id' => $user->id,
                'specialty' => $p['specialty'],
                'has_day_job' => $p['has_day_job'],
                'day_job_desc' => $p['day_job_desc'],
                'day_job_start' => $p['has_day_job'] ? '08:00:00' : null,
                'day_job_end' => $p['has_day_job'] ? '16:00:00' : null,
                'is_active' => true,
                'is_backup' => $p['is_backup'],
            ]);
        }

        // 2 Klien Users
        $klien1 = User::create(['name' => 'Bpk. Hendar', 'email' => 'hendar@gmail.com', 'password' => Hash::make('password'), 'role' => 'klien']);
        $klien2 = User::create(['name' => 'Ibu Ratna Sari', 'email' => 'ratna@gmail.com', 'password' => Hash::make('password'), 'role' => 'klien']);
        $klien3 = User::create(['name' => 'PT. Mandala Events', 'email' => 'mandala@events.co.id', 'password' => Hash::make('password'), 'role' => 'klien']);

        // ═══════════════════════════════════════════════════
        // 2. FEE REFERENCES (Tarif per Role)
        // ═══════════════════════════════════════════════════
        $feePenariUtama = FeeReference::create(['role_name' => 'Penari Utama', 'base_fee' => 350000, 'description' => 'Peran utama koreografi']);
        $feePenariLatar = FeeReference::create(['role_name' => 'Penari Latar',  'base_fee' => 250000, 'description' => 'Pendukung formasi tari']);
        $feeMusik       = FeeReference::create(['role_name' => 'Pemusik',       'base_fee' => 300000, 'description' => 'Pengiring musik gamelan/degung']);
        $feeCadangan    = FeeReference::create(['role_name' => 'Cadangan',      'base_fee' => 200000, 'description' => 'Standby multi-talent']);

        // ═══════════════════════════════════════════════════
        // 3. BOOKINGS (5 Booking Fiktif)
        // ═══════════════════════════════════════════════════
        $booking1 = Booking::create([
            'client_id' => $klien1->id, 'client_name' => 'Bpk. Hendar', 'client_phone' => '081234567890',
            'event_type' => 'jaipong', 'event_date' => '2026-04-12', 'event_start' => '19:00', 'event_end' => '22:00',
            'venue' => 'Gedung Serbaguna Karawaci', 'venue_address' => 'Jl. Karawaci Raya No. 45, Tangerang',
            'total_price' => 15000000, 'dp_amount' => 7500000, 'status' => 'dp_paid', 'booking_source' => 'web',
            'dp_paid_at' => '2026-03-28 10:00:00',
        ]);

        $booking2 = Booking::create([
            'client_id' => $klien3->id, 'client_name' => 'PT. Mandala Events', 'client_phone' => '082155667788',
            'event_type' => 'degung', 'event_date' => '2026-04-16', 'event_start' => '10:00', 'event_end' => '13:00',
            'venue' => 'Hotel Aston BSD', 'venue_address' => 'Jl. BSD Grand Boulevard, Tangerang Selatan',
            'total_price' => 12000000, 'dp_amount' => 6000000, 'status' => 'dp_paid', 'booking_source' => 'admin_manual',
            'dp_paid_at' => '2026-03-30 09:00:00',
        ]);

        $booking3 = Booking::create([
            'client_id' => $klien2->id, 'client_name' => 'Ibu Ratna Sari', 'client_phone' => '085266778899',
            'event_type' => 'rampak_gendang', 'event_date' => '2026-04-25', 'event_start' => '14:00', 'event_end' => '17:00',
            'venue' => 'Balai Kota Subang', 'venue_address' => 'Jl. Otto Iskandar Dinata No. 1, Subang',
            'total_price' => 18000000, 'dp_amount' => 9000000, 'status' => 'confirmed', 'booking_source' => 'web',
            'dp_paid_at' => '2026-03-25 14:30:00',
        ]);

        $booking4 = Booking::create([
            'client_name' => 'Bpk. Soleh', 'client_phone' => '089912345678',
            'event_type' => 'jaipong', 'event_date' => '2026-05-10', 'event_start' => '19:30', 'event_end' => '22:30',
            'venue' => 'Pendopo Bupati Purwakarta', 'total_price' => 20000000, 'dp_amount' => 10000000,
            'status' => 'pending', 'booking_source' => 'admin_manual',
        ]);

        $booking5 = Booking::create([
            'client_id' => $klien1->id, 'client_name' => 'Bpk. Hendar', 'client_phone' => '081234567890',
            'event_type' => 'degung', 'event_date' => '2026-03-20', 'event_start' => '10:00', 'event_end' => '12:00',
            'venue' => 'Rumah Klien - Cikarang', 'total_price' => 8000000, 'dp_amount' => 4000000,
            'status' => 'cancelled', 'booking_source' => 'web', 'dp_paid_at' => '2026-03-10 08:00:00',
        ]);

        // ═══════════════════════════════════════════════════
        // 4. EVENTS (3 Event Aktif dari 3 Booking Pertama)
        // ═══════════════════════════════════════════════════
        $event1 = Event::create([
            'booking_id' => $booking1->id, 'event_code' => 'EVT-2026-001', 'status' => 'ready',
            'event_date' => '2026-04-12', 'event_start' => '19:00', 'event_end' => '22:00',
            'venue' => 'Gedung Serbaguna Karawaci', 'personnel_count' => 12,
            'estimated_total_honor' => 3400000,
        ]);

        $event2 = Event::create([
            'booking_id' => $booking2->id, 'event_code' => 'EVT-2026-002', 'status' => 'planning',
            'event_date' => '2026-04-16', 'event_start' => '10:00', 'event_end' => '13:00',
            'venue' => 'Hotel Aston BSD', 'personnel_count' => 12,
            'estimated_total_honor' => 0,
        ]);

        $event3 = Event::create([
            'booking_id' => $booking3->id, 'event_code' => 'EVT-2026-003', 'status' => 'ready',
            'event_date' => '2026-04-25', 'event_start' => '14:00', 'event_end' => '17:00',
            'venue' => 'Balai Kota Subang', 'personnel_count' => 12,
            'estimated_total_honor' => 3400000,
        ]);

        // ═══════════════════════════════════════════════════
        // 5. EVENT_PERSONNEL (Plotting untuk Event 1 & 3)
        // ═══════════════════════════════════════════════════
        $roles = ['penari_utama', 'penari_utama', 'penari_latar', 'penari_latar', 'penari_latar',
                   'pemusik', 'pemusik', 'pemusik', 'penari_latar', 'penari_latar', 'penari_utama', 'cadangan'];
        $fees  = [$feePenariUtama, $feePenariUtama, $feePenariLatar, $feePenariLatar, $feePenariLatar,
                  $feeMusik, $feeMusik, $feeMusik, $feePenariLatar, $feePenariLatar, $feePenariUtama, $feeCadangan];

        foreach ($personnelRecords as $i => $p) {
            // Event 1: Fully plotted, beberapa sudah check-in
            $checkedIn = $i < 8; // 8 pertama sudah check-in
            DB::table('event_personnel')->insert([
                'event_id' => $event1->id,
                'personnel_id' => $p->id,
                'fee_reference_id' => $fees[$i]->id,
                'role_in_event' => $roles[$i],
                'status' => 'confirmed',
                'fee' => $fees[$i]->base_fee,
                'checked_in_at' => $checkedIn ? '2026-04-12 18:30:00' : null,
                'attendance_status' => $checkedIn ? 'on_time' : 'not_arrived',
                'late_minutes' => ($i === 6) ? 25 : 0, // Dadan telat 25 menit
                'created_at' => now(), 'updated_at' => now(),
            ]);

            // Event 3: Plotted
            DB::table('event_personnel')->insert([
                'event_id' => $event3->id,
                'personnel_id' => $p->id,
                'fee_reference_id' => $fees[$i]->id,
                'role_in_event' => $roles[$i],
                'status' => 'assigned',
                'fee' => $fees[$i]->base_fee,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ═══════════════════════════════════════════════════
        // 6. FINANCIAL RECORDS (3 Event)
        // ═══════════════════════════════════════════════════
        $fr1 = FinancialRecord::create([
            'event_id' => $event1->id, 'total_revenue' => 15000000,
            'fixed_profit_pct' => 30, 'is_profit_overridden' => false,
            'fixed_profit' => 4500000, 'dp_received' => 7500000,
            'total_personnel_honor' => 3400000, 'operational_budget' => 3000000,
            'actual_operational_cost' => 1850000, 'net_profit' => 5650000,
            'safety_buffer_pct' => 10, 'safety_buffer_amt' => 300000,
            'budget_warning' => false, 'profit_locked' => true, 'status' => 'locked',
        ]);

        $fr2 = FinancialRecord::create([
            'event_id' => $event2->id, 'total_revenue' => 12000000,
            'fixed_profit_pct' => 30, 'is_profit_overridden' => false,
            'fixed_profit' => 3600000, 'dp_received' => 6000000,
            'total_personnel_honor' => 0, 'operational_budget' => 2400000,
            'actual_operational_cost' => 0, 'net_profit' => 0,
            'safety_buffer_pct' => 10, 'safety_buffer_amt' => 240000,
            'budget_warning' => false, 'profit_locked' => true, 'status' => 'locked',
        ]);

        $fr3 = FinancialRecord::create([
            'event_id' => $event3->id, 'total_revenue' => 18000000,
            'fixed_profit_pct' => 30, 'is_profit_overridden' => false,
            'fixed_profit' => 5400000, 'dp_received' => 9000000,
            'total_personnel_honor' => 3400000, 'operational_budget' => 3600000,
            'actual_operational_cost' => 800000, 'net_profit' => 0,
            'safety_buffer_pct' => 10, 'safety_buffer_amt' => 360000,
            'budget_warning' => false, 'profit_locked' => true, 'status' => 'locked',
        ]);

        // ═══════════════════════════════════════════════════
        // 7. OPERATIONAL COSTS (Event 1 realistis)
        // ═══════════════════════════════════════════════════
        OperationalCost::create(['financial_record_id' => $fr1->id, 'category' => 'bensin', 'description' => 'BBM Avanza 2 mobil PP', 'estimated_amount' => 500000, 'actual_amount' => 650000, 'updated_by' => $admin->id]);
        OperationalCost::create(['financial_record_id' => $fr1->id, 'category' => 'konsumsi', 'description' => 'Nasi box 15 porsi', 'estimated_amount' => 450000, 'actual_amount' => 500000, 'updated_by' => $admin->id]);
        OperationalCost::create(['financial_record_id' => $fr1->id, 'category' => 'parkir', 'description' => 'Parkir Gedung + Tol', 'estimated_amount' => 150000, 'actual_amount' => 200000, 'updated_by' => $admin->id]);
        OperationalCost::create(['financial_record_id' => $fr1->id, 'category' => 'lainnya', 'description' => 'Tip security venue', 'estimated_amount' => 100000, 'actual_amount' => 100000, 'updated_by' => $admin->id]);
        OperationalCost::create(['financial_record_id' => $fr1->id, 'category' => 'sewa_kostum', 'description' => 'Sewa 5 set Jaipong Bandung', 'estimated_amount' => 300000, 'actual_amount' => 400000, 'updated_by' => $admin->id]);

        OperationalCost::create(['financial_record_id' => $fr3->id, 'category' => 'bensin', 'description' => 'BBM 1 mobil lokal', 'estimated_amount' => 200000, 'actual_amount' => 200000, 'updated_by' => $admin->id]);
        OperationalCost::create(['financial_record_id' => $fr3->id, 'category' => 'konsumsi', 'description' => 'Nasi kotak 15 porsi', 'estimated_amount' => 450000, 'actual_amount' => 450000, 'updated_by' => $admin->id]);
        OperationalCost::create(['financial_record_id' => $fr3->id, 'category' => 'parkir', 'description' => 'Parkir Balai Kota', 'estimated_amount' => 50000, 'actual_amount' => 50000, 'updated_by' => $admin->id]);
        OperationalCost::create(['financial_record_id' => $fr3->id, 'category' => 'lainnya', 'description' => 'Dekorasi panggung tambahan', 'estimated_amount' => 100000, 'actual_amount' => 100000, 'updated_by' => $admin->id]);

        // ═══════════════════════════════════════════════════
        // 8. COSTUME VENDORS & RENTALS
        // ═══════════════════════════════════════════════════
        $vendor1 = CostumeVendor::create(['name' => 'Rumah Kostum Bandung', 'city' => 'Bandung', 'phone' => '081122334455', 'address' => 'Jl. Braga No.12, Bandung', 'return_deadline_days' => 3]);
        $vendor2 = CostumeVendor::create(['name' => 'Megah Costume Subang', 'city' => 'Subang', 'phone' => '085544332211', 'address' => 'Jl. Otista No.5, Subang', 'return_deadline_days' => 3]);

        CostumeRental::create([
            'event_id' => $event1->id, 'vendor_id' => $vendor1->id,
            'costume_type' => 'Set Jaipong Premium', 'quantity' => 5,
            'rental_date' => '2026-04-10', 'due_date' => '2026-04-14',
            'returned_date' => null, 'status' => 'rented', 'rental_cost' => 750000,
        ]);
        CostumeRental::create([
            'event_id' => $event3->id, 'vendor_id' => $vendor2->id,
            'costume_type' => 'Set Rampak Gendang', 'quantity' => 8,
            'rental_date' => '2026-04-23', 'due_date' => '2026-04-27',
            'returned_date' => null, 'status' => 'rented', 'rental_cost' => 1200000,
        ]);

        // ═══════════════════════════════════════════════════
        // 9. SANGGAR COSTUMES (Aset Milik Sanggar)
        // ═══════════════════════════════════════════════════
        SanggarCostume::create(['name' => 'Kebaya Jaipong Merah', 'category' => 'atasan', 'condition' => 'good', 'quantity' => 5]);
        SanggarCostume::create(['name' => 'Sampur Emas', 'category' => 'aksesoris', 'condition' => 'good', 'quantity' => 12]);
        SanggarCostume::create(['name' => 'Sinjang Batik', 'category' => 'bawahan', 'condition' => 'good', 'quantity' => 8]);
        SanggarCostume::create(['name' => 'Mahkota Siger', 'category' => 'aksesoris', 'condition' => 'damaged', 'quantity' => 2]);
        SanggarCostume::create(['name' => 'Gondang (Gendang Besar)', 'category' => 'alat_musik', 'condition' => 'good', 'quantity' => 4]);

        // ═══════════════════════════════════════════════════
        // 10. CANCELLATION (Booking 5)
        // ═══════════════════════════════════════════════════
        Cancellation::create([
            'booking_id' => $booking5->id, 'cancellation_date' => '2026-03-18',
            'days_before_event' => 2, 'penalty_percentage' => 75, 'penalty_amount' => 6000000,
            'refund_amount' => 0, 'status' => 'processed',
            'reason' => 'Ada keluarga yang sakit, acara ditunda tidak tentu.', 'digital_acknowledgement' => true,
        ]);
    }
}
