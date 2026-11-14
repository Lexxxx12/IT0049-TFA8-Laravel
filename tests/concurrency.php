<?php

use App\Actions\RecordSale;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

// Run only against the dedicated test database after the PHPUnit suite.
putenv('DB_CONNECTION=mysql');
putenv('DB_DATABASE=it0049_pos_test');
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

if (($argv[1] ?? '') === 'worker') {
    try {
        app(RecordSale::class)->execute(['product_id' => (int) $argv[2], 'quantity' => 4, 'submission_token' => (string) Str::uuid()], (int) $argv[3]);
        echo 'sold';
    } catch (ValidationException) {
        echo 'rejected';
    }exit;
}
$u = User::create(['username' => 'race_'.Str::random(8), 'full_name' => 'Race Test', 'password' => Str::random(20)]);
$p = Product::create(['name' => 'Concurrency test', 'price' => '19.99', 'stock_quantity' => 5]);
$children = [];
for ($i = 0; $i < 2; $i++) {
    $pipes = [];
    $process = proc_open([PHP_BINARY, __FILE__, 'worker', (string) $p->id, (string) $u->id], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    $children[] = [$process, $pipes];
}
$output = [];
foreach ($children as [$process,$pipes]) {
    $output[] = stream_get_contents($pipes[1]);
    $error = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    if (proc_close($process) !== 0) {
        throw new RuntimeException($error);
    }
}
sort($output);
if ($output !== ['rejected', 'sold'] || $p->fresh()->stock_quantity !== 1 || Sale::where('product_id', $p->id)->count() !== 1) {
    throw new RuntimeException('Concurrency invariant failed: '.json_encode($output));
}
Sale::where('product_id', $p->id)->delete();
$p->forceDelete();
$u->forceDelete();
echo "PASS: concurrent sales sold once; stock = 1.\n";
