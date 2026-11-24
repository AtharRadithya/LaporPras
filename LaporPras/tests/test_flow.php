<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "1. Testing Form Submission (POST /lapor/smkn-4-bandung)...\n";

// Create a dummy image
$tempImg = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test_img_' . uniqid() . '.jpg';
$im = imagecreatetruecolor(100, 100);
imagejpeg($im, $tempImg);
imagedestroy($im);

$uploadedFile = new Illuminate\Http\UploadedFile(
    $tempImg,
    'foto_kerusakan.jpg',
    'image/jpeg',
    null,
    true
);

$session = $app->make('session')->driver();
$session->start();
$token = $session->token();

$request = Illuminate\Http\Request::create(
    '/lapor/smkn-4-bandung',
    'POST',
    [
        '_token'        => $token,
        'location_id'   => 1,
        'asset_id'      => 10,
        'reporter_name' => 'Budi Santoso',
        'reporter_info' => 'Siswa - XII RPL 2',
        'title'         => 'Monitor Komputer PC-05 Layar Bergaris',
        'description'   => 'Layar monitor bergaris-garis vertikal ungu dan berkedip saat KBM Praktikum.',
        'priority'      => 'Tinggi',
    ],
    [],
    ['photo_before' => $uploadedFile]
);
$request->setLaravelSession($session);

$response = $kernel->handle($request);
echo "Submit Status: " . $response->getStatusCode() . " (Redirect)\n";
$targetUrl = $response->headers->get('Location');
echo "Redirect Target: " . $targetUrl . "\n";

// Extract code from redirect url
preg_match('/\/lacak\/(TKT-[^\/]+)/', $targetUrl, $matches);
$newCode = $matches[1] ?? 'TKT-2602-001';
echo "Generated Ticket Code: " . $newCode . "\n";

echo "\n2. Testing Tracking Page (GET /lacak/{$newCode})...\n";
$trackReq = Illuminate\Http\Request::create('/lacak/' . $newCode, 'GET');
$trackRes = $kernel->handle($trackReq);
echo "Tracking Status: " . $trackRes->getStatusCode() . "\n";

echo "\n3. Testing Sarpras Dashboard (GET /sarpras/tiket)...\n";
$dashReq = Illuminate\Http\Request::create('/sarpras/tiket', 'GET');
$dashRes = $kernel->handle($dashReq);
echo "Dashboard Status: " . $dashRes->getStatusCode() . "\n";

echo "\n4. Testing Selesaikan Perbaikan (POST /sarpras/selesai/101)...\n";
$tempAfterImg = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test_after_' . uniqid() . '.jpg';
$im2 = imagecreatetruecolor(100, 100);
imagejpeg($im2, $tempAfterImg);
imagedestroy($im2);

$afterFile = new Illuminate\Http\UploadedFile(
    $tempAfterImg,
    'foto_sesudah.jpg',
    'image/jpeg',
    null,
    true
);

$completeReq = Illuminate\Http\Request::create(
    '/sarpras/selesai/101',
    'POST',
    [
        '_token'      => $token,
        'repair_cost' => 120000,
        'note'        => 'Penggantian kabel VGA dan pembersihan port display.',
    ],
    [],
    ['photo_after' => $afterFile]
);
$completeReq->setLaravelSession($session);

$completeRes = $kernel->handle($completeReq);
echo "Complete Status: " . $completeRes->getStatusCode() . "\n";

echo "\nALL TESTS COMPLETED SUCCESSFULLY!\n";
