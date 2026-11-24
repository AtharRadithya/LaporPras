<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/sarpras/selesai/101', 'GET');
$response = $kernel->handle($request);

echo "Selesai Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() >= 400 && method_exists($response, 'exception') && $response->exception) {
    echo "Exception: " . $response->exception->getMessage() . "\n";
    echo $response->exception->getFile() . ":" . $response->exception->getLine() . "\n";
}
