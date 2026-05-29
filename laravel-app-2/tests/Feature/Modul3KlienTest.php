<?php

use App\Models\User;
use App\Models\Booking;
use App\Models\ServiceCatalog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Buat Service Catalog untuk test
    $this->catalog = ServiceCatalog::create([
        'name' => 'Tari Tradisional',
        'description' => 'Tarian khas',
        'price' => 500000,
        'image' => 'catalogs/dummy.jpg',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    // Hapus user test jika ada
    User::where('email', 'test_klien@example.com')->delete();

    // Hapus booking pada tanggal test agar tidak bentrok dengan data persisten
    Booking::whereIn('event_date', [
        now()->addDays(5)->format('Y-m-d'),
        now()->subDays(2)->format('Y-m-d')
    ])->delete();

    // Buat Klien
    $this->klien = User::create([
        'name' => 'Test Klien',
        'email' => 'test_klien@example.com',
        'password' => bcrypt('password123'),
        'role' => 'klien',
        'phone' => '081234567890',
    ]);
});

// KL-01: Booking Baru – Tanggal Kosong (Valid)
test('KL-01: Booking Baru – Tanggal Kosong (Valid)', function () {
    $this->actingAs($this->klien);

    $date = now()->addDays(5)->format('Y-m-d');
    
    $response = $this->post(route('klien.bookings.store'), [
        'service_catalog_id' => $this->catalog->id,
        'event_date' => $date,
        'event_start' => '10:00',
        'event_end' => '12:00',
        'venue' => 'Gedung Sate',
        'client_phone' => '081234567890',
        'venue_address' => 'Bandung'
    ]);

    $booking = Booking::where('client_id', $this->klien->id)->first();
    expect($booking)->not->toBeNull();
    expect($booking->status)->toBe('pending');
    
    $response->assertRedirect(route('klien.bookings.show', $booking->id));
});

// KL-02: Booking – Tanggal Sudah Penuh
test('KL-02: Booking – Tanggal Sudah Penuh', function () {
    $this->actingAs($this->klien);

    $date = now()->addDays(5)->format('Y-m-d');

    // Booking pertama yang sudah DP paid
    Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => '081234567890',
        'event_type'         => $this->catalog->name,
        'service_catalog_id' => $this->catalog->id,
        'event_date'         => $date,
        'event_start'        => '09:00',
        'event_end'          => '11:00',
        'venue'              => 'Gedung Sate',
        'status'             => 'dp_paid',
        'total_price'        => 500000,
        'dp_amount'          => 250000,
    ]);

    // Coba booking di tanggal yang sama
    $response = $this->post(route('klien.bookings.store'), [
        'service_catalog_id' => $this->catalog->id,
        'event_date' => $date,
        'event_start' => '10:00',
        'event_end' => '12:00',
        'venue' => 'Gedung Sate',
        'client_phone' => '081234567890',
    ]);

    $response->assertSessionHasErrors('event_date');
});

// KL-03: Upload Bukti DP – File Valid (JPG < 5MB)
test('KL-03: Upload Bukti DP – File Valid (JPG < 5MB)', function () {
    Storage::fake('public');
    
    $this->actingAs($this->klien);
    
    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => '081234567890',
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

    $jpegBase64 = '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA=';
    $file = UploadedFile::fake()->createWithContent('bukti_dp.jpg', base64_decode($jpegBase64));

    $response = $this->post(route('klien.bookings.upload_proof', $booking->id), [
        'payment_proof' => $file
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
    
    $booking->refresh();
    expect($booking->payment_proof)->not->toBeNull();
});

// KL-04: Upload Bukti DP – File Terlalu Besar
test('KL-04: Upload Bukti DP – File Terlalu Besar', function () {
    Storage::fake('public');
    
    $this->actingAs($this->klien);
    
    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => '081234567890',
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

    // File 6MB with valid jpeg header
    $jpegBase64 = '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA=';
    $largeContent = base64_decode($jpegBase64) . str_repeat('0', 6 * 1024 * 1024);
    $file = UploadedFile::fake()->createWithContent('bukti_dp.jpg', $largeContent);

    $response = $this->post(route('klien.bookings.upload_proof', $booking->id), [
        'payment_proof' => $file
    ]);

    $response->assertSessionHasErrors('payment_proof');
});

// KL-05: Upload Bukti DP – Format Tidak Didukung
test('KL-05: Upload Bukti DP – Format Tidak Didukung', function () {
    Storage::fake('public');
    
    $this->actingAs($this->klien);
    
    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => '081234567890',
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

    // File .txt
    $file = UploadedFile::fake()->createWithContent('bukti_dp.txt', 'This is a text file');

    $response = $this->post(route('klien.bookings.upload_proof', $booking->id), [
        'payment_proof' => $file
    ]);

    $response->assertSessionHasErrors('payment_proof');
});

// KL-06: Upload Bukti Pelunasan (Full Payment)
test('KL-06: Upload Bukti Pelunasan (Full Payment)', function () {
    Storage::fake('public');
    
    $this->actingAs($this->klien);
    
    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => '081234567890',
        'event_type'         => $this->catalog->name,
        'service_catalog_id' => $this->catalog->id,
        'event_date'         => now()->addDays(5)->format('Y-m-d'),
        'event_start'        => '09:00',
        'event_end'          => '11:00',
        'venue'              => 'Gedung Sate',
        'status'             => 'dp_paid', // Harus dp_paid
        'total_price'        => 500000,
        'dp_amount'          => 250000,
    ]);

    $jpegBase64 = '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA=';
    $file = UploadedFile::fake()->createWithContent('bukti_lunas.jpg', base64_decode($jpegBase64));

    $response = $this->post(route('klien.bookings.upload_full_proof', $booking->id), [
        'full_payment_proof' => $file
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
    
    $booking->refresh();
    expect($booking->full_payment_proof)->not->toBeNull();
});

// KL-07: Melihat Riwayat Booking
test('KL-07: Melihat Riwayat Booking', function () {
    $this->actingAs($this->klien);
    
    Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => '081234567890',
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

    $response = $this->get(route('klien.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Gedung Sate');
});

// KL-08: Submit Feedback / Ulasan
test('KL-08: Submit Feedback / Ulasan', function () {
    $this->actingAs($this->klien);
    
    $booking = Booking::create([
        'client_id'          => $this->klien->id,
        'client_name'        => $this->klien->name,
        'client_phone'       => '081234567890',
        'event_type'         => $this->catalog->name,
        'service_catalog_id' => $this->catalog->id,
        'event_date'         => now()->subDays(2)->format('Y-m-d'), // Di masa lalu
        'event_start'        => '09:00',
        'event_end'          => '11:00',
        'venue'              => 'Gedung Sate',
        'status'             => 'completed', // Harus completed
        'total_price'        => 500000,
        'dp_amount'          => 250000,
    ]);

    $response = $this->post(route('klien.bookings.feedback', $booking->id), [
        'rating' => 5,
        'testimony' => 'Bagus sekali!'
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('client_feedbacks', [
        'booking_id' => $booking->id,
        'rating' => 5,
        'testimony' => 'Bagus sekali!'
    ]);
});

// KL-09: Edit Profil Klien
test('KL-09: Edit Profil Klien', function () {
    $this->actingAs($this->klien);
    
    $response = $this->put(route('klien.profile.update'), [
        'name' => 'Klien Baru Edit',
        'email' => 'klien_edit@example.com',
        'phone' => '089999999999',
    ]);

    $response->assertRedirect();
    $this->klien->refresh();
    expect($this->klien->name)->toBe('Klien Baru Edit');
    expect($this->klien->phone)->toBe('089999999999');
});

// KL-10: Ganti Password Klien – Password Lama Salah
test('KL-10: Ganti Password Klien – Password Lama Salah', function () {
    $this->actingAs($this->klien);
    
    $response = $this->put(route('klien.profile.password'), [
        'current_password' => 'wrongpassword',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors('current_password');
});
