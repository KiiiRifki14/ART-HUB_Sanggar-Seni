<?php

use App\Models\User;
use App\Models\Booking;
use App\Models\ServiceCatalog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Buat Admin
    $this->admin = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Test Admin',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]
    );

    // Buat Service Catalog untuk test
    $this->catalog = ServiceCatalog::firstOrCreate(
        ['name' => 'Tari Tradisional'],
        [
            'description' => 'Tarian khas',
            'price' => 500000,
            'image' => 'catalogs/dummy.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]
    );

    // Buat Klien
    $this->klien = User::firstOrCreate(
        ['email' => 'test_klien@example.com'],
        [
            'name' => 'Test Klien',
            'password' => bcrypt('password123'),
            'role' => 'klien',
            'phone' => '081234567890',
        ]
    );

    \Illuminate\Support\Facades\DB::unprepared("
        DROP FUNCTION IF EXISTS fn_calculate_cancellation_penalty;
        CREATE FUNCTION fn_calculate_cancellation_penalty(
            p_event_date DATE,
            p_cancel_date DATE,
            p_total_price DECIMAL(15,2)
        ) RETURNS DECIMAL(15,2)
        DETERMINISTIC
        BEGIN
            DECLARE v_days_diff INT;
            DECLARE v_penalty DECIMAL(15,2);
            
            SET v_days_diff = DATEDIFF(p_event_date, p_cancel_date);
            
            IF v_days_diff >= 14 THEN
                SET v_penalty = p_total_price * 0.10;
            ELSEIF v_days_diff >= 7 THEN
                SET v_penalty = p_total_price * 0.25;
            ELSEIF v_days_diff >= 3 THEN
                SET v_penalty = p_total_price * 0.50;
            ELSE
                SET v_penalty = p_total_price * 1.00;
            END IF;
            
            RETURN v_penalty;
        END;
    ");
});

// ADM-01: Verifikasi & Konfirmasi Pembayaran DP
test('ADM-01: Verifikasi & Konfirmasi Pembayaran DP', function () {
    $this->actingAs($this->admin);

    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => $this->klien->phone,
        'event_type'         => $this->catalog->name,
        'service_catalog_id' => $this->catalog->id,
        'event_date'         => now()->addDays(5)->format('Y-m-d'),
        'event_start'        => '09:00',
        'event_end'          => '11:00',
        'venue'              => 'Gedung Sate',
        'status'             => 'pending',
        'payment_proof'      => 'proofs/dummy.jpg',
        'total_price'        => 500000,
        'dp_amount'          => 250000,
    ]);

    // Asumsikan nama route-nya admin.bookings.confirm
    // Mari cek nama route di web.php (kemungkinan bookings.confirm)
    // Wait, let's use the explicit URI if the route name is not sure, but I'll use the controller action or assume it's bookings.confirm_payment or similar. 
    // In web.php I saw `confirmPayment` and `confirmCashPayment`. Let's assume the route name is 'admin.bookings.confirm'. I'll check web.php to be sure if this fails.
    // Let's use string URL: /admin/bookings/{id}/confirm
    $response = $this->post('/admin/bookings/' . $booking->id . '/confirm', [
        'fixed_profit_nominal' => 100000,
    ]);

    $response->assertRedirect();
    $booking->refresh();
    expect($booking->status)->toBe('dp_paid');
    
    // Check Event is generated
    expect($booking->event)->not->toBeNull();
    // Check FinancialRecord is generated
    expect($booking->event->financialRecord)->not->toBeNull();
});

// ADM-02: Tolak Bukti Transfer DP
test('ADM-02: Tolak Bukti Transfer DP', function () {
    $this->actingAs($this->admin);

    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => $this->klien->phone,
        'event_type'         => $this->catalog->name,
        'service_catalog_id' => $this->catalog->id,
        'event_date'         => now()->addDays(5)->format('Y-m-d'),
        'event_start'        => '09:00',
        'event_end'          => '11:00',
        'venue'              => 'Gedung Sate',
        'status'             => 'pending',
        'payment_proof'      => 'proofs/dummy.jpg',
        'total_price'        => 500000,
        'dp_amount'          => 250000,
    ]);

    $response = $this->post(route('admin.bookings.reject_proof', $booking->id));

    $response->assertRedirect();
    $booking->refresh();
    expect($booking->payment_proof)->toBeNull();
    expect($booking->status)->toBe('pending');
});

// ADM-03: Booking Manual (Offline/Tunai)
test('ADM-03: Booking Manual (Offline/Tunai)', function () {
    $this->actingAs($this->admin);

    $response = $this->post(route('admin.bookings.manual.store'), [
        'client_name' => 'Klien Offline',
        'client_phone' => '08111111111',
        'event_type' => 'Tari Tradisional',
        'service_catalog_id' => $this->catalog->id,
        'event_date' => now()->addDays(20)->format('Y-m-d'),
        'event_start' => '10:00',
        'event_end' => '12:00',
        'venue' => 'Aula Desa',
        'total_price' => 500000,
        'dp_amount' => 250000,
    ]);

    $response->dumpSession();
    $response->assertRedirect();
    
    $booking = Booking::where('client_name', 'Klien Offline')->first();
    expect($booking)->not->toBeNull();
    expect($booking->status)->toBe('pending');
});

// ADM-04: Konfirmasi Pelunasan (Full Payment)
test('ADM-04: Konfirmasi Pelunasan (Full Payment)', function () {
    $this->actingAs($this->admin);

    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => $this->klien->phone,
        'event_type'         => $this->catalog->name,
        'service_catalog_id' => $this->catalog->id,
        'event_date'         => now()->addDays(5)->format('Y-m-d'),
        'event_start'        => '09:00',
        'event_end'          => '11:00',
        'venue'              => 'Gedung Sate',
        'status'             => 'dp_paid',
        'full_payment_proof' => 'proofs/lunas.jpg',
        'total_price'        => 500000,
        'dp_amount'          => 250000,
    ]);

    $response = $this->patch(route('admin.bookings.full_payment', $booking->id));

    $response->assertRedirect();
    $booking->refresh();
    expect($booking->status)->toBe('paid_full');
});

// ADM-05: Tolak Bukti Pelunasan
test('ADM-05: Tolak Bukti Pelunasan', function () {
    $this->actingAs($this->admin);

    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => $this->klien->phone,
        'event_type'         => $this->catalog->name,
        'service_catalog_id' => $this->catalog->id,
        'event_date'         => now()->addDays(5)->format('Y-m-d'),
        'event_start'        => '09:00',
        'event_end'          => '11:00',
        'venue'              => 'Gedung Sate',
        'status'             => 'dp_paid',
        'full_payment_proof' => 'proofs/lunas.jpg',
        'total_price'        => 500000,
        'dp_amount'          => 250000,
    ]);

    $response = $this->post(route('admin.bookings.reject_full_proof', $booking->id));

    $response->assertRedirect();
    $booking->refresh();
    expect($booking->status)->toBe('dp_paid');
    expect($booking->full_payment_proof)->toBeNull();
});

// ADM-06: Pembatalan Booking
test('ADM-06: Pembatalan Booking', function () {
    $this->actingAs($this->admin);

    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => $this->klien->phone,
        'event_type'         => $this->catalog->name,
        'service_catalog_id' => $this->catalog->id,
        'event_date'         => now()->addDays(5)->format('Y-m-d'),
        'event_start'        => '09:00',
        'event_end'          => '11:00',
        'venue'              => 'Gedung Sate',
        'status'             => 'pending',
        'total_price'        => 500000,
        'dp_amount'          => 250000,
    ]);

    $response = $this->post(route('admin.bookings.cancel', $booking->id), [
        'reason' => 'Diminta oleh klien',
        'digital_acknowledgement' => 1
    ]);

    $response->dumpSession();
    $response->assertRedirect();
    $booking->refresh();
    expect($booking->status)->toBe('cancelled');
    
    // Check if cancellation log is created
    $this->assertDatabaseHas('cancellations', [
        'booking_id' => $booking->id,
    ]);
});

// ADM-07: Konfirmasi Pelunasan Cash
test('ADM-07: Konfirmasi Pelunasan Cash', function () {
    $this->actingAs($this->admin);

    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => $this->klien->phone,
        'event_type'         => $this->catalog->name,
        'service_catalog_id' => $this->catalog->id,
        'event_date'         => now()->addDays(30)->format('Y-m-d'), // Tanggal sangat jauh (30 hari ke depan)
        'event_start'        => '09:00',
        'event_end'          => '11:00',
        'venue'              => 'Gedung Sate',
        'status'             => 'dp_paid', // status awal dp_paid
        'total_price'        => 500000,
        'dp_amount'          => 250000,
    ]);

    // Tambah event & financial record agar controller pelunasan cash tidak error
    $event = \App\Models\Event::create([
        'booking_id' => $booking->id,
        'event_code' => 'EVT-CASH-TEST',
        'event_date' => $booking->event_date,
        'event_start' => now()->addDays(30)->setTime(9, 0),
        'event_end' => now()->addDays(30)->setTime(11, 0),
        'venue' => 'Gedung Sate',
        'status' => 'planning',
    ]);

    \App\Models\FinancialRecord::create([
        'event_id' => $event->id,
        'total_revenue' => $booking->total_price,
        'fixed_profit' => 100000,
        'operational_budget' => 150000,
        'safety_buffer_amt' => 15000,
        'profit_locked' => true,
    ]);

    $response = $this->post(route('admin.bookings.full_cash_payment', $booking->id));

    $response->assertRedirect();
    $booking->refresh();
    expect($booking->status)->toBe('paid_full');
    expect($booking->full_paid_at)->not->toBeNull();
});
