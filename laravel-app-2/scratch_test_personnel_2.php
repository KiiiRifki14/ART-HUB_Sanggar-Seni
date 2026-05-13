<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

$personnel = Personnel::with('user')->first();
echo "Before: is_active = " . ($personnel->is_active ? '1' : '0') . ", has_day_job = " . ($personnel->has_day_job ? '1' : '0') . "\n";

$request = Request::create('/test', 'PUT', [
    'name' => 'Test Name',
    'specialty' => 'penari',
    'is_active' => '0',
    'has_day_job' => '0',
]);

DB::transaction(function () use ($request, $personnel) {
    $personnel->user->name = $request->name;
    $personnel->user->save();

    $personnel->specialty = $request->specialty;
    $personnel->has_day_job = $request->boolean('has_day_job');
    $personnel->is_active = $request->boolean('is_active');
    $personnel->is_backup = $request->boolean('is_backup');
    $personnel->save();
});

$personnel->refresh();
echo "After: is_active = " . ($personnel->is_active ? '1' : '0') . ", has_day_job = " . ($personnel->has_day_job ? '1' : '0') . "\n";
