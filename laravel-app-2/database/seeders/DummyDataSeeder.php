<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SanggarCostume;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Delete klien test users
        User::where('role', 'klien')
            ->where(function ($query) {
                $query->where('name', 'like', '%test%')
                      ->orWhere('email', 'like', '%test%');
            })->delete();

        // 2. Add dummy costumes
        SanggarCostume::insert([
            [
                'name' => 'Kostum Jaipong Kembang',
                'category' => 'Tari Jaipong',
                'quantity' => 10,
                'condition' => 'good',
                'storage_location' => 'Lemari A1',
                'description' => 'Kostum lengkap dengan sinjang dan sampur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kostum Merak',
                'category' => 'Tari Merak',
                'quantity' => 8,
                'condition' => 'good',
                'storage_location' => 'Lemari B2',
                'description' => 'Kostum tari merak dengan sayap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pakaian Pangsi',
                'category' => 'Pakaian Adat',
                'quantity' => 15,
                'condition' => 'good',
                'storage_location' => 'Lemari C',
                'description' => 'Pakaian pangsi hitam standar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kostum Tari Topeng',
                'category' => 'Tari Topeng',
                'quantity' => 5,
                'condition' => 'maintenance',
                'storage_location' => 'Rak 1',
                'description' => 'Baju dan topeng kayu kelana',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kebaya Modern',
                'category' => 'Pakaian Adat',
                'quantity' => 20,
                'condition' => 'good',
                'storage_location' => 'Lemari D',
                'description' => 'Kebaya modern warna warni',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
