<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicReportController;
use App\Http\Controllers\SarprasTicketController;

// 1. Halaman Form Laporan Publik Tanpa Login (Uji SP 1)
Route::get('/lapor/{slug}', function ($slug) {
    return view('public.lapor', ['slug' => $slug]);
})->name('public.form');

Route::post('/lapor/{slug}', [PublicReportController::class, 'store'])->name('public.store');

// Halaman Lacak Kode Tiket setelah melapor
Route::get('/lacak/{code}', function ($code) {
    return view('public.lacak', ['code' => $code]);
})->name('public.track');

// 2. Halaman Form Penyelesaian Perbaikan Sarpras (Uji SP 2)
Route::get('/sarpras/selesai/{ticketId}', function ($ticketId) {
    return view('sarpras.form_selesai', ['ticketId' => $ticketId]);
})->name('sarpras.form_selesai');

Route::post('/sarpras/selesai/{ticketId}', [SarprasTicketController::class, 'completeRepair'])
     ->name('sarpras.complete');

Route::get('/sarpras/tiket', [SarprasTicketController::class, 'index'])->name('sarpras.index');