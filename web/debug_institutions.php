<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $institutions = App\Models\Institution::select('id', 'code', 'is_active', 'is_internal')->get();
    foreach ($institutions as $i) {
        echo "ID: {$i->id} | Code: {$i->code} | Active: {$i->is_active} | Internal: {$i->is_internal}\n";
    }
} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage();
}
