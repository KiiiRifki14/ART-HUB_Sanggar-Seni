<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    //
});

// AUTH-01 | Login Valid – Role Admin
test('AUTH-01: Admin can login successfully', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin_test@test.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'phone' => '081234567890'
    ]);

    $response = $this->post('/login', [
        'email' => 'admin_test@test.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($admin);
    $response->assertRedirect(route('dashboard'));
});

// AUTH-02 | Login Valid – Role Personel
test('AUTH-02: Personel can login successfully', function () {
    $personel = User::create([
        'name' => 'Personel User',
        'email' => 'personel_test@test.com',
        'password' => Hash::make('password123'),
        'role' => 'personel',
        'phone' => '081234567891'
    ]);
    // Create personel details
    \App\Models\Personnel::create([
        'user_id' => $personel->id,
        'specialty' => 'penari',
        'is_active' => true,
    ]);

    $response = $this->post('/login', [
        'email' => 'personel_test@test.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($personel);
    $response->assertRedirect(route('dashboard'));
});

// AUTH-03 | Login Valid – Role Klien
test('AUTH-03: Klien can login successfully', function () {
    $klien = User::create([
        'name' => 'Klien User',
        'email' => 'klien_test@test.com',
        'password' => Hash::make('password123'),
        'role' => 'klien',
        'phone' => '081234567892'
    ]);

    $response = $this->post('/login', [
        'email' => 'klien_test@test.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($klien);
    $response->assertRedirect(route('dashboard'));
});

// AUTH-04 | Login Password Salah
test('AUTH-04: User cannot login with wrong password', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'wrongpass_test@test.com',
        'password' => Hash::make('password123'),
        'role' => 'klien',
        'phone' => '081234567893'
    ]);

    $response = $this->post('/login', [
        'email' => 'wrongpass_test@test.com',
        'password' => 'wrongpassword',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

// AUTH-05 | Login Email Tidak Terdaftar
test('AUTH-05: Cannot login with unregistered email', function () {
    $response = $this->post('/login', [
        'email' => 'notfound@test.com',
        'password' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

// AUTH-06 | Registrasi Klien Baru – Valid
test('AUTH-06: Klien can register with valid data and OTP', function () {
    $email = 'new_klien@test.com';
    $otp = '123456';
    Cache::put('register_otp:' . $email, $otp, now()->addMinutes(5));

    $response = $this->post('/register', [
        'name' => 'New Klien',
        'email' => $email,
        'phone' => '081299998888',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'klien',
        'otp_code' => $otp,
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => $email,
        'role' => 'klien'
    ]);
    $response->assertRedirect(route('dashboard'));
});

// AUTH-07 | Registrasi – Email Sudah Digunakan
test('AUTH-07: Cannot register if email already taken', function () {
    User::create([
        'name' => 'Existing User',
        'email' => 'taken@test.com',
        'password' => Hash::make('password123'),
        'role' => 'klien',
        'phone' => '081234567895'
    ]);

    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => 'taken@test.com',
        'phone' => '081299998888',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'klien',
        'otp_code' => '123456',
    ]);

    $response->assertSessionHasErrors('email');
});

// AUTH-08 | Registrasi – Password < 8 Karakter
test('AUTH-08: Cannot register with password less than 8 characters', function () {
    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => 'shortpass@test.com',
        'phone' => '081299998888',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'klien',
        'otp_code' => '123456',
    ]);

    $response->assertSessionHasErrors('password');
});

// AUTH-09 | Akses Halaman Admin Tanpa Login
test('AUTH-09: Unauthenticated user cannot access admin page', function () {
    $response = $this->get('/admin/dashboard');
    $response->assertRedirect('/login');
});

// AUTH-10 | Akses Halaman Admin dengan Akun Klien
test('AUTH-10: Klien cannot access admin page', function () {
    $klien = User::create([
        'name' => 'Klien User',
        'email' => 'klien_admin_test@test.com',
        'password' => Hash::make('password123'),
        'role' => 'klien',
        'phone' => '081234567896'
    ]);

    $response = $this->actingAs($klien)->get('/admin/dashboard');
    $response->assertRedirect('/klien/dashboard'); // Klien dikembalikan ke dashboardnya sendiri
});
