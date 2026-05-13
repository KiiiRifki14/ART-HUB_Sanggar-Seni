<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Personnel;

$personnel = Personnel::first();
echo "Before: is_active = " . ($personnel->is_active ? 'true' : 'false') . "\n";

$personnel->is_active = false;
$personnel->save();

$personnel->refresh();
echo "After: is_active = " . ($personnel->is_active ? 'true' : 'false') . "\n";
