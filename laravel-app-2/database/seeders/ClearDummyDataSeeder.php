<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Menonaktifkan foreign key checks agar proses truncate berjalan lancar tanpa error constraint
        Schema::disableForeignKeyConstraints();

        // ═══════════════════════════════════════════════════
        // 1. TRUNCATE TABEL TRANSAKSI & LOG DUMMY
        // ═══════════════════════════════════════════════════
        $tablesToTruncate = [
            'financial_audits',
            'operational_costs',
            'financial_records',
            'event_logs',
            'client_feedbacks',
            'event_personnel',
            'rehearsals',
            'costume_usages',
            'costume_rentals',
            'cancellations',
            'events',
            'bookings',
            'personnel_schedules',
            'personnel_unavailabilities',
            'notifications',
            'sessions', // Clear semua session aktif
        ];

        foreach ($tablesToTruncate as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        // ═══════════════════════════════════════════════════
        // 2. HAPUS AKUN KLIEN DUMMY
        // ═══════════════════════════════════════════════════
        if (Schema::hasTable('users')) {
            DB::table('users')->where('role', 'klien')->delete();
        }

        // Mengaktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}
