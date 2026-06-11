<?php

/**
 * @var Tests\TestCase $this
 * @property \App\Models\User $admin
 * @property \App\Models\ServiceCatalog $catalog
 * @property \App\Models\Booking $booking
 * @property \App\Models\Event $event
 * @property \App\Models\User $personnelUser
 * @property \App\Models\Personnel $personnel
 * @property \App\Models\Rehearsal $rehearsal
 */

use App\Models\User;
use App\Models\Booking;
use App\Models\Event;
use App\Models\ServiceCatalog;
use App\Models\Personnel;
use App\Models\FeeReference;
use App\Models\Rehearsal;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

uses(DatabaseTransactions::class);

beforeEach(function () {
    Carbon::setTestNow('2026-06-11 12:00:00');

    $this->admin = User::firstOrCreate(
        ['email' => 'admin_test6@example.com'],
        [
            'name' => 'Admin Test 6',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]
    );

    $this->catalog = ServiceCatalog::firstOrCreate(
        ['name' => 'Tari Tradisional 3'],
        [
            'description' => 'Tarian khas',
            'price' => 600000,
            'image' => 'catalogs/dummy.jpg',
            'is_active' => true,
            'sort_order' => 1,
            'max_personnel' => 2,
            'specialty_type' => 'gabungan',
        ]
    );

    $this->booking = Booking::firstOrCreate(
        ['client_email' => 'klien_test6@example.com'],
        [
            'client_id'          => null,
            'client_name'        => 'Klien Test 6',
            'client_phone'       => '081122334455',
            'event_type'         => $this->catalog->name,
            'service_catalog_id' => $this->catalog->id,
            'event_date'         => now()->addDays(20)->format('Y-m-d'),
            'event_start'        => '09:00',
            'event_end'          => '11:00',
            'venue'              => 'Pendopo Utama Sanggar',
            'status'             => 'dp_paid',
            'total_price'        => 600000,
            'dp_amount'          => 300000,
        ]
    );

    $this->event = Event::firstOrCreate(
        ['booking_id' => $this->booking->id],
        [
            'event_code' => 'EVT-TEST-006',
            'status' => 'ready',
            'event_date' => $this->booking->event_date,
            'event_start' => $this->booking->event_start,
            'event_end' => $this->booking->event_end,
            'venue' => $this->booking->venue,
            'personnel_count' => 2,
        ]
    );

    $this->personnelUser = User::firstOrCreate(
        ['email' => 'personnel6@example.com'],
        [
            'name' => 'Personel 6',
            'password' => bcrypt('password123'),
            'role' => 'personel',
        ]
    );

    $this->personnel = Personnel::firstOrCreate(
        ['user_id' => $this->personnelUser->id],
        [
            'stage_name' => 'Penari 6',
            'specialty' => 'penari',
            'is_active' => true,
            'status' => 'active',
        ]
    );

    // Plotting personel ke event
    DB::table('event_personnel')->updateOrInsert(
        ['event_id' => $this->event->id, 'personnel_id' => $this->personnel->id],
        [
            'fee_reference_id' => 1,
            'role_in_event' => 'penari_utama',
            'status' => 'assigned',
            'fee' => 150000,
        ]
    );
});

afterEach(function () {
    Carbon::setTestNow(null);
});

// TEST-01: Pagination index personel dan index latihan
test('TEST-01: Pagination index personel dan index latihan', function () {
    $this->actingAs($this->admin);

    $responsePersonel = $this->get(route('admin.personnel.index'));
    $responsePersonel->assertStatus(200);

    $responseRehearsals = $this->get(route('admin.rehearsals.index'));
    $responseRehearsals->assertStatus(200);
});

// TEST-02: Membuat Jadwal Latihan Baru dengan Maps Koordinat
test('TEST-02: Membuat Jadwal Latihan Baru dengan Maps Koordinat', function () {
    $this->actingAs($this->admin);

    $response = $this->post(route('admin.rehearsals.store', $this->event->id), [
        'type' => 'tari',
        'rehearsal_date' => now()->addDays(5)->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'location' => 'Pendopo Utama Sanggar',
        'latitude' => -6.9175,
        'longitude' => 107.6062,
        'notes' => 'Latihan Tari Rampak Gendang',
        'force_save' => 1
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('rehearsals', [
        'event_id' => $this->event->id,
        'type' => 'tari',
        'location' => 'Pendopo Utama Sanggar',
        'latitude' => -6.91750000,
        'longitude' => 107.60620000,
    ]);

    expect($this->event->fresh()->status)->toBe('rehearsal');
});

// TEST-03: Absen Latihan Kru Sukses (Dalam Radius 200 Meter)
test('TEST-03: Absen Latihan Kru Sukses', function () {
    // Buat rehearsal hari ini
    $rehearsal = Rehearsal::create([
        'event_id' => $this->event->id,
        'type' => 'tari',
        'rehearsal_date' => now()->format('Y-m-d'),
        'start_time' => now()->subMinutes(10)->format('H:i'), // call time masih masuk toleransi
        'end_time' => now()->addHours(1)->format('H:i'),
        'location' => 'Pendopo Utama Sanggar',
        'latitude' => -6.9175,
        'longitude' => 107.6062,
    ]);

    $this->actingAs($this->personnelUser);

    $response = $this->post(route('personnel.rehearsals.check_in', $rehearsal->id), [
        'latitude' => -6.9175,
        'longitude' => 107.6062,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('rehearsal_personnel', [
        'rehearsal_id' => $rehearsal->id,
        'personnel_id' => $this->personnel->id,
        'attendance_status' => 'on_time',
    ]);
});

// TEST-04: Absen Latihan Kru Gagal (Di Luar Radius 200 Meter)
test('TEST-04: Absen Latihan Kru Gagal Di Luar Radius 200m', function () {
    // Buat rehearsal hari ini
    $rehearsal = Rehearsal::create([
        'event_id' => $this->event->id,
        'type' => 'tari',
        'rehearsal_date' => now()->format('Y-m-d'),
        'start_time' => now()->format('H:i'),
        'end_time' => now()->addHours(1)->format('H:i'),
        'location' => 'Pendopo Utama Sanggar',
        'latitude' => -6.9175,
        'longitude' => 107.6062,
    ]);

    $this->actingAs($this->personnelUser);

    // Kirim koordinat di Jakarta (di luar Bandung)
    $response = $this->post(route('personnel.rehearsals.check_in', $rehearsal->id), [
        'latitude' => -6.2088,
        'longitude' => 106.8456,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $this->assertDatabaseMissing('rehearsal_personnel', [
        'rehearsal_id' => $rehearsal->id,
        'personnel_id' => $this->personnel->id,
        'attendance_status' => 'on_time',
    ]);
});

// TEST-05: Absen Latihan Kru Gagal (Di Luar Jendela Waktu Pengerjaan Sah)
test('TEST-05: Absen Latihan Kru Gagal Di Luar Jendela Waktu', function () {
    // Buat rehearsal hari ini tetapi 3 jam lagi baru mulai
    $rehearsal = Rehearsal::create([
        'event_id' => $this->event->id,
        'type' => 'tari',
        'rehearsal_date' => now()->format('Y-m-d'),
        'start_time' => now()->addHours(3)->format('H:i'),
        'end_time' => now()->addHours(5)->format('H:i'),
        'location' => 'Pendopo Utama Sanggar',
        'latitude' => -6.9175,
        'longitude' => 107.6062,
    ]);

    $this->actingAs($this->personnelUser);

    $response = $this->post(route('personnel.rehearsals.check_in', $rehearsal->id), [
        'latitude' => -6.9175,
        'longitude' => 107.6062,
        'accuracy' => 10,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $this->assertDatabaseMissing('rehearsal_personnel', [
        'rehearsal_id' => $rehearsal->id,
        'personnel_id' => $this->personnel->id,
    ]);
});

// TEST-06: Absen Latihan Kru Gagal (Akurasi Sensor Jelek >= 50 meter)
test('TEST-06: Absen Latihan Kru Gagal Akurasi Sensor Jelek', function () {
    $rehearsal = Rehearsal::create([
        'event_id' => $this->event->id,
        'type' => 'tari',
        'rehearsal_date' => now()->format('Y-m-d'),
        'start_time' => now()->subMinutes(10)->format('H:i'),
        'end_time' => now()->addHours(1)->format('H:i'),
        'location' => 'Pendopo Utama Sanggar',
        'latitude' => -6.9175,
        'longitude' => 107.6062,
    ]);

    $this->actingAs($this->personnelUser);

    $response = $this->post(route('personnel.rehearsals.check_in', $rehearsal->id), [
        'latitude' => -6.9175,
        'longitude' => 107.6062,
        'accuracy' => 55, // di atas 50 meter
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $this->assertDatabaseMissing('rehearsal_personnel', [
        'rehearsal_id' => $rehearsal->id,
        'personnel_id' => $this->personnel->id,
    ]);
});

// TEST-07: Cascading Cleanup Pivot pada Deletion
test('TEST-07: Cascading Cleanup Pivot pada Deletion', function () {
    $rehearsal = Rehearsal::create([
        'event_id' => $this->event->id,
        'type' => 'tari',
        'rehearsal_date' => now()->format('Y-m-d'),
        'start_time' => now()->subMinutes(10)->format('H:i'),
        'end_time' => now()->addHours(1)->format('H:i'),
        'location' => 'Pendopo Utama Sanggar',
        'latitude' => -6.9175,
        'longitude' => 107.6062,
    ]);

    // Hubungkan dengan pivot
    DB::table('rehearsal_personnel')->insert([
        'rehearsal_id' => $rehearsal->id,
        'personnel_id' => $this->personnel->id,
        'checked_in_at' => now(),
        'attendance_status' => 'on_time',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->assertDatabaseHas('rehearsal_personnel', [
        'rehearsal_id' => $rehearsal->id,
        'personnel_id' => $this->personnel->id,
    ]);

    // Hapus model Rehearsal
    $rehearsal->delete();

    $this->assertDatabaseMissing('rehearsal_personnel', [
        'rehearsal_id' => $rehearsal->id,
        'personnel_id' => $this->personnel->id,
    ]);
});
