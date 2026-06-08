<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$personnel = App\Models\Personnel::with(['user', 'events'])->where('is_active', false)->get();
foreach ($personnel as $p) {
    echo "ID: {$p->id}, Name: " . ($p->user->name ?? 'N/A') . ", Event Count: " . $p->events->count() . "\n";
}
