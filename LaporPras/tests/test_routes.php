<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$routes = [
    '/'                              => 'Beranda / Portal Utama',
    '/lapor/smkn-4-bandung'          => 'Form Lapor Kerusakan (Default)',
    '/lapor/smkn-4-bandung?ruangan=1'=> 'Form Lapor (QR Scan ID: 1)',
    '/lapor/smkn-4-bandung?ruangan=tkj' => 'Form Lapor (QR Scan Slug: tkj)',
    '/lacak'                         => 'Lacak Tiket (Pencarian)',
    '/lacak/TKT-2602-001'            => 'Lacak Tiket (Detail)',
    '/sarpras/tiket'                 => 'Dashboard Admin Sarpras (Stripe Style)',
    '/sarpras/selesai/101'           => 'Form Penyelesaian Perbaikan (Uji SP 2)',
    '/sarpras/export/csv'            => 'Export CSV Rekapitulasi Sarpras',
];

echo "========================================================\n";
echo "  PENGUJIAN RUTE LAPORPRAS - SMKN 4 BANDUNG\n";
echo "========================================================\n";

$allPassed = true;

foreach ($routes as $uri => $description) {
    try {
        $request = Illuminate\Http\Request::create($uri, 'GET');
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        $ok = ($status >= 200 && $status < 400);
        
        $badge = $ok ? "[OK]" : "[FAIL]";
        echo sprintf("%-6s %-32s -> %-35s (Status: %d)\n", $badge, $uri, $description, $status);
        
        if (!$ok) {
            $allPassed = false;
        }
    } catch (\Throwable $e) {
        echo sprintf("[ERR]  %-32s -> EXCEPTION: %s\n", $uri, $e->getMessage());
        $allPassed = false;
    }
}

// Test Status Update AJAX / POST
try {
    $postReq = Illuminate\Http\Request::create('/sarpras/status/42', 'POST', [
        'status' => 'Diproses',
        'note'   => 'Teknisi menuju lokasi'
    ]);
    $postReq->headers->set('Accept', 'application/json');
    $postRes = $kernel->handle($postReq);
    $status = $postRes->getStatusCode();
    $ok = ($status >= 200 && $status < 400);
    echo sprintf("%-6s %-32s -> %-35s (Status: %d)\n", $ok ? "[OK]" : "[FAIL]", "POST /sarpras/status/42", "Update Status Laporan (Drawer)", $status);
} catch (\Throwable $e) {
    echo sprintf("[ERR]  POST /sarpras/status/42 -> EXCEPTION: %s\n", $e->getMessage());
}

echo "========================================================\n";
echo $allPassed ? "  HASIL: SEMUA RUTE BERHASIL DIUJI (100% OK)\n" : "  HASIL: ADA RUTE YANG GAGAL\n";
echo "========================================================\n";
