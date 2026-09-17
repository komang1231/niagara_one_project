<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $t = microtime(true);
    $d = \App\Models\Departemen::create([
        'nama' => 'Create Script '.date('YmdHis'),
        'status' => 'aktif',
    ]);
    $ms = round((microtime(true)-$t)*1000,2);
    echo "OK in {$ms} ms\n";
    print_r($d->toArray());
} catch (\Throwable $e) {
    echo 'ERR: '.$e->getMessage()."\n";
    echo $e;
}
