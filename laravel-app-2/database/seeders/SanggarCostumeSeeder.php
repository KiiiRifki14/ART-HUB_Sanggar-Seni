<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanggarCostumeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sanggar_costumes')->insert([
            [
                'name'             => 'Kostum Jaipong Set A (Merah-Emas)',
                'category'         => 'jaipong',
                'quantity'         => 6,
                'condition'        => 'good',
                'storage_location' => 'Lemari A - Rak 1',
                'description'      => 'Set lengkap: kebaya, samping, selendang. Warna merah-emas.',
                'last_cleaned_at'  => now()->subDays(7),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Kostum Jaipong Set B (Hijau-Emas)',
                'category'         => 'jaipong',
                'quantity'         => 6,
                'condition'        => 'good',
                'storage_location' => 'Lemari A - Rak 2',
                'description'      => 'Set lengkap: kebaya, samping, selendang. Warna hijau-emas.',
                'last_cleaned_at'  => now()->subDays(14),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Kostum Rampak Gendang',
                'category'         => 'rampak',
                'quantity'         => 8,
                'condition'        => 'good',
                'storage_location' => 'Lemari B - Rak 1',
                'description'      => 'Seragam penabuh gendang tradisional.',
                'last_cleaned_at'  => now()->subDays(5),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Ikat Kepala Batik',
                'category'         => 'aksesoris',
                'quantity'         => 12,
                'condition'        => 'good',
                'storage_location' => 'Lemari C - Rak 1',
                'description'      => 'Ikat kepala batik untuk pemusik.',
                'last_cleaned_at'  => now()->subDays(3),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'name'             => 'Topeng Klana (Reog)',
                'category'         => 'topeng',
                'quantity'         => 2,
                'condition'        => 'maintenance',
                'storage_location' => 'Lemari D - Display',
                'description'      => 'Topeng Klana untuk tarian Reog/Topeng. Sedang diperbaiki cat.',
                'last_cleaned_at'  => now()->subDays(30),
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);
    }
}
