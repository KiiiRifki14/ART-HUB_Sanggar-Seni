<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Cek apakah SP masih ada
$exists = DB::select("SHOW PROCEDURE STATUS LIKE 'sp_check_personnel_availability'");
echo "SP masih ada: " . (count($exists) > 0 ? 'YA' : 'TIDAK') . PHP_EOL;

// Tampilkan isi SP saat ini
try {
    $proc = DB::select("SHOW CREATE PROCEDURE sp_check_personnel_availability");
    if ($proc) {
        echo "Definition: " . ($proc[0]->{'Create Procedure'} ?? 'null') . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Tidak bisa tampilkan definition: " . $e->getMessage() . PHP_EOL;
}
