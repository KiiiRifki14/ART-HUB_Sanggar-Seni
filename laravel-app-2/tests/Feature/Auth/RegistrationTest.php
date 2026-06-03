<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new clients can register', function () {
    $email = 'test@example.com';
    \Illuminate\Support\Facades\Cache::put('register_otp:' . $email, '123456', now()->addMinutes(15));

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => $email,
        'phone' => '08123456789',
        'role' => 'klien',
        'otp_code' => '123456',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('new personnel can register', function () {
    $email = 'personnel@example.com';
    \Illuminate\Support\Facades\Cache::put('register_otp:' . $email, '123456', now()->addMinutes(15));

    $response = $this->post('/register', [
        'name' => 'Personnel User',
        'email' => $email,
        'phone' => '08123456780',
        'role' => 'personnel',
        'specialty' => 'penari',
        'otp_code' => '123456',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('personnel.pending', absolute: false));
});
