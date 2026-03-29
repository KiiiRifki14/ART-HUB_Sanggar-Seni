<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SampleBookingSeeder extends Seeder
{
    public function run(): void
    {
        $klien1Id = DB::table('users')->where('email', 'sari@email.com')->value('id');
        $klien2Id = DB::table('users')->where('email', 'hendra@email.com')->value('id');

        // ═══ Booking 1: Sudah DP (dari klien web) ═══
        DB::table('bookings')->insert([
            'client_id'       => $klien1Id,
            'client_name'     => null,
            'client_phone'    => null,
            'client_email'    => null,
            'event_type'      => 'jaipong',
            'event_date'      => Carbon::now()->addDays(21)->toDateString(),
            'event_start'     => '19:00',
            'event_end'       => '22:00',
            'venue'           => 'Gedung Serbaguna Karawaci',
            'venue_address'   => 'Jl. Karawaci Raya No. 88, Tangerang',
            'total_price'     => 15000000,
            'dp_amount'       => 7500000,
            'payment_receipt' => null,
            'status'          => 'pending',
            'booking_source'  => 'web',
            'client_notes'    => 'Untuk acara pernikahan anak. Mohon tarian Jaipong klasik.',
            'admin_notes'     => null,
            'dp_paid_at'      => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // ═══ Booking 2: Pending (dari klien web) ═══
        DB::table('bookings')->insert([
            'client_id'       => $klien2Id,
            'client_name'     => null,
            'client_phone'    => null,
            'client_email'    => null,
            'event_type'      => 'degung',
            'event_date'      => Carbon::now()->addDays(35)->toDateString(),
            'event_start'     => '10:00',
            'event_end'       => '13:00',
            'venue'           => 'Hotel Aston BSD',
            'venue_address'   => 'Jl. BSD Raya No. 15, Tangerang Selatan',
            'total_price'     => 12000000,
            'dp_amount'       => 6000000,
            'payment_receipt' => null,
            'status'          => 'pending',
            'booking_source'  => 'web',
            'client_notes'    => 'Acara gathering kantor. Butuh musik Degung untuk suasana makan siang.',
            'admin_notes'     => null,
            'dp_paid_at'      => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // ═══ Booking 3: Quick Entry Admin (klien tanpa akun) ═══
        DB::table('bookings')->insert([
            'client_id'       => null,
            'client_name'     => 'Ibu Ratna Puspita',
            'client_phone'    => '087865432100',
            'client_email'    => 'ratna.puspita@gmail.com',
            'event_type'      => 'jaipong',
            'event_date'      => Carbon::now()->addDays(14)->toDateString(),
            'event_start'     => '14:00',
            'event_end'       => '17:00',
            'venue'           => 'Rumah Klien',
            'venue_address'   => 'Jl. Anggrek No. 3, Cikupa, Tangerang',
            'total_price'     => 10000000,
            'dp_amount'       => 5000000,
            'payment_receipt' => null,
            'status'          => 'pending',
            'booking_source'  => 'admin_manual',
            'client_notes'    => null,
            'admin_notes'     => 'Nego via WA. Klien minta diskon, sudah disetujui 10jt.',
            'dp_paid_at'      => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }
}
