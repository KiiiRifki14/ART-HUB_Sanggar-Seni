<?php

use App\Models\ServiceCatalog;
use App\Models\Personnel;
use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    //
});

// LP-01 | Tampil Katalog Jasa
test('LP-01: Tampil Katalog Jasa', function () {
    // Create an active service catalog
    $catalog = ServiceCatalog::create([
        'name' => 'Tari Tradisional Jaipong Test',
        'description' => 'Tarian khas Jawa Barat',
        'price' => 500000,
        'image' => 'catalogs/dummy.jpg',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('Tari Tradisional Jaipong Test');
});

// LP-02 | Tampil Profil Personel
test('LP-02: Tampil Profil Personel', function () {
    // Create an active personnel
    $user = User::create([
        'name' => 'Nyi Iteung Test',
        'email' => 'iteung_test@test.com',
        'password' => Hash::make('password123'),
        'role' => 'personel',
        'phone' => '081122334455'
    ]);
    Personnel::create([
        'user_id' => $user->id,
        'specialty' => 'penari',
        'bio' => 'Lincah dan menawan',
        'is_active' => true,
    ]);

    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('Nyi Iteung Test');
    $response->assertSee('Penari');
});

// LP-03 | Tombol WA Mengarah ke Nomor yang Benar
test('LP-03: Tombol Hubungi Kami Mengarah ke Email yang Benar', function () {
    // Ensure footer_email exists in SiteContent
    SiteContent::updateOrCreate(
        ['key' => 'footer_email'],
        ['value' => 'test@cahayagumilang.id', 'type' => 'text']
    );

    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('test@cahayagumilang.id'); // Should be in the href of email button
});
