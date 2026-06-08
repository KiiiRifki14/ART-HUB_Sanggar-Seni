<?php

use App\Models\User;
use App\Models\Booking;
use App\Models\Event;
use App\Models\ServiceCatalog;
use App\Models\Personnel;
use App\Models\FeeReference;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->admin = User::firstOrCreate(
        ['email' => 'admin_evt@example.com'],
        [
            'name' => 'Admin Event',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]
    );

    $this->catalog = ServiceCatalog::firstOrCreate(
        ['name' => 'Tari Tradisional 2'],
        [
            'description' => 'Tarian khas',
            'price' => 500000,
            'image' => 'catalogs/dummy.jpg',
            'is_active' => true,
            'sort_order' => 1,
            'max_personnel' => 2,
            'specialty_type' => 'gabungan',
        ]
    );

    $this->booking = Booking::firstOrCreate(
        ['client_email' => 'klien_evt@example.com'],
        [
            'client_id'          => null,
            'client_name'        => 'Klien Evt',
            'client_phone'       => '0811223344',
            'event_type'         => $this->catalog->name,
            'service_catalog_id' => $this->catalog->id,
            'event_date'         => now()->addDays(20)->format('Y-m-d'),
            'event_start'        => '09:00',
            'event_end'          => '11:00',
            'venue'              => 'Gedung Sate',
            'status'             => 'dp_paid',
            'total_price'        => 500000,
            'dp_amount'          => 250000,
        ]
    );

    $this->event = Event::firstOrCreate(
        ['booking_id' => $this->booking->id],
        [
            'event_code' => 'EVT-TEST-001',
            'status' => 'planning',
            'event_date' => $this->booking->event_date,
            'event_start' => $this->booking->event_start,
            'event_end' => $this->booking->event_end,
            'venue' => $this->booking->venue,
            'personnel_count' => 2,
        ]
    );

    $this->personnelUser = User::firstOrCreate(
        ['email' => 'personnel1@example.com'],
        [
            'name' => 'Personel 1',
            'password' => bcrypt('password123'),
            'role' => 'personel',
        ]
    );

    $this->personnel = Personnel::firstOrCreate(
        ['user_id' => $this->personnelUser->id],
        [
            'stage_name' => 'Penari 1',
            'specialty' => 'penari',
            'is_active' => true,
        ]
    );

    $this->feeRef = FeeReference::firstOrCreate(
        ['role_name' => 'Penari Utama'],
        [
            'base_fee' => 150000,
            'is_active' => true,
        ]
    );
});

// EVT-01: Smart Plotting Normal (Personel Tersedia)
test('EVT-01: Smart Plotting Normal', function () {
    $this->actingAs($this->admin);

    $response = $this->post(route('admin.events.plotting.store', $this->event->id), [
        'personnel' => [
            [
                'selected' => 1,
                'id' => $this->personnel->id,
                'fee_reference_id' => $this->feeRef->id,
                'role_in_event' => 'penari_utama',
            ]
        ]
    ]);

    $response->dumpSession();
    $response->assertRedirect();
    $this->assertDatabaseHas('event_personnel', [
        'event_id' => $this->event->id,
        'personnel_id' => $this->personnel->id,
        'fee_reference_id' => $this->feeRef->id,
    ]);
});

// EVT-04: Update Koordinat GPS Lokasi Event
test('EVT-04: Update Koordinat GPS Lokasi Event', function () {
    $this->actingAs($this->admin);

    $response = $this->patch(route('admin.events.update_coordinates', $this->event->id), [
        'latitude' => -6.902481,
        'longitude' => 107.61881,
    ]);

    $response->assertRedirect();
    $this->event->refresh();
    expect((float)$this->event->latitude)->toBe(-6.902481);
});

// EVT-05: Tandai Event Selesai
test('EVT-05: Tandai Event Selesai', function () {
    $this->actingAs($this->admin);

    $response = $this->patch(route('admin.events.mark_completed', $this->event->id));

    $response->assertRedirect();
    $this->event->refresh();
    expect($this->event->status)->toBe('completed');
    expect($this->booking->fresh()->status)->toBe('completed');
});

// EVT-06: Sync Event Personnel Count on Service Catalog Update
test('EVT-06: Sync Event Personnel Count on Service Catalog Update', function () {
    $this->actingAs($this->admin);

    // Update catalog max_personnel to 8
    $response = $this->put(route('admin.catalogs.update', $this->catalog->id), [
        'name' => $this->catalog->name,
        'description' => $this->catalog->description,
        'detail' => $this->catalog->detail,
        'price' => $this->catalog->price,
        'badge' => $this->catalog->badge,
        'sort_order' => $this->catalog->sort_order,
        'is_active' => $this->catalog->is_active,
        'max_personnel' => 8,
        'specialty_type' => $this->catalog->specialty_type,
    ]);

    $response->assertRedirect();
    $this->event->refresh();
    expect($this->event->personnel_count)->toBe(8);
});

// EVT-07: Update Status Tugas Personel Per-Event
test('EVT-07: Update Status Tugas Personel Per-Event', function () {
    $this->actingAs($this->admin);

    // Plotting personel ke event terlebih dahulu jika belum ter-plot
    if (!$this->event->personnel()->where('personnel.id', $this->personnel->id)->exists()) {
        $this->event->personnel()->attach($this->personnel->id, [
            'fee_reference_id' => $this->feeRef->id,
            'role_in_event' => 'penari_utama',
            'status' => 'assigned'
        ]);
    }

    // Update status tugas menjadi 'Lagi Latihan'
    $response = $this->patch(route('admin.personnel.update_event_status', [$this->event->id, $this->personnel->id]), [
        'status' => 'Lagi Latihan'
    ]);

    $response->assertRedirect();
    
    // Verifikasi di database pivot event_personnel
    $this->assertDatabaseHas('event_personnel', [
        'event_id' => $this->event->id,
        'personnel_id' => $this->personnel->id,
        'status' => 'Lagi Latihan'
    ]);

    // Verifikasi status personel di tabel master tetap 'active' (tidak terpengaruh)
    $this->personnel->refresh();
    expect($this->personnel->status)->toBe('active');
});

