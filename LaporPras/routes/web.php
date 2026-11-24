<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicReportController;
use App\Http\Controllers\SarprasTicketController;

/*
|--------------------------------------------------------------------------
| Web Routes - LaporPras (Pelaporan & Manajemen Sarpras Sekolah)
|--------------------------------------------------------------------------
| Tugas Siswa: Kennan - XII RPL
| Penguji: Pa Ali & Bu Arne
| Terintegrasi Stored Procedure Oracle APEX / ORDS:
| - sp_buat_laporan_rusak (Uji SP 1)
| - sp_selesaikan_perbaikan & sf_total_biaya_perbaikan (Uji SP 2)
*/

// Beranda & Portal Navigasi Utama
Route::get('/', function () {
    return view('home');
})->name('home');

// ==========================================
// 1. MODUL PELAPOR PUBLIK (MOBILE FIRST)
// ==========================================
Route::get('/lapor', function () {
    return redirect()->route('public.form', ['slug' => 'smkn-4-bandung']);
});

Route::get('/lapor/{slug}', [PublicReportController::class, 'create'])->name('public.form');
Route::post('/lapor/{slug}', [PublicReportController::class, 'store'])->name('public.store');

// Halaman Lacak Status Tiket Kerusakan
Route::get('/lacak', [PublicReportController::class, 'track'])->name('public.track.search');
Route::get('/lacak/{code}', [PublicReportController::class, 'track'])->name('public.track');


// ==========================================
// 2. MODUL ADMIN / TEKNISI SARPRAS
// ==========================================
Route::get('/sarpras', function () {
    return redirect()->route('sarpras.index');
});

Route::get('/sarpras/tiket', [SarprasTicketController::class, 'index'])->name('sarpras.index');
Route::get('/sarpras/export/csv', [SarprasTicketController::class, 'exportCsv'])->name('sarpras.export.csv');

// Form & Eksekusi Penyelesaian Perbaikan (Uji SP 2) & Quick Status Update
Route::get('/sarpras/selesai/{ticketId}', [SarprasTicketController::class, 'formSelesai'])->name('sarpras.form_selesai');
Route::post('/sarpras/selesai/{ticketId}', [SarprasTicketController::class, 'completeRepair'])->name('sarpras.complete');
Route::post('/sarpras/status/{ticketId}', [SarprasTicketController::class, 'updateStatus'])->name('sarpras.status.update');
