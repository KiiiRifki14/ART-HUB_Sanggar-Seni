<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password OTP can be requested', function () {
    Mail::fake();

    $user = User::factory()->create();

    $response = $this->post('/forgot-password', ['email' => $user->email]);

    $response->assertRedirect(route('password.reset.otp.form'));
    $this->assertEquals($user->email, session('reset_email'));

    Mail::assertSent(OtpMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('reset password OTP screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->withSession(['reset_email' => $user->email])
        ->get('/reset-password-otp');

    $response->assertStatus(200);
});

test('password can be reset with valid OTP', function () {
    $user = User::factory()->create([
        'otp_code' => '123456',
        'otp_expires_at' => now()->addMinutes(15),
    ]);

    $response = $this->post('/reset-password-otp', [
        'email' => $user->email,
        'otp_code' => '123456',
        'password' => 'New-password123',
        'password_confirmation' => 'New-password123',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('login'));

    $this->assertTrue(Hash::check('New-password123', $user->refresh()->password));
    $this->assertNull($user->otp_code);
    $this->assertNull($user->otp_expires_at);
});
