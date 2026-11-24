<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PublicReportController extends Controller
{
    public function store(Request $request, $slug)
    {
        $request->validate([
            'location_id'   => 'required|integer',
            'asset_id'      => 'nullable|integer',
            'reporter_name' => 'required|string|max:100',
            'reporter_info' => 'required|string|max:100',
            'title'         => 'required|string|max:150',
            'description'   => 'required|string',
            'photo_before'  => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        // Simpan foto bukti kerusakan ke storage Laravel
        $photoPath = $request->file('photo_before')->store('tickets/before', 'public');

        // Kirim data ke REST API Oracle APEX (memanggil sp_buat_laporan_rusak)
        $response = Http::post(env('ORACLE_ORDS_URL') . '/tiket/lapor', [
            'p_school_slug'   => $slug,
            'p_location_id'   => (int) $request->input('location_id'),
            'p_asset_id'      => $request->input('asset_id') ? (int) $request->input('asset_id') : null,
            'p_reporter_name' => $request->input('reporter_name'),
            'p_reporter_info' => $request->input('reporter_info'),
            'p_title'         => $request->input('title'),
            'p_description'   => $request->input('description'),
            'p_photo_before'  => $photoPath,
            'p_priority'      => $request->input('priority', 'Sedang'),
        ]);

        $hasil = $response->json();
        $ticketCode  = $hasil['p_ticket_code'] ?? '-';
        $statusPesan = $hasil['p_status_pesan'] ?? 'Gagal memproses laporan.';

        return redirect()->route('public.track', ['code' => $ticketCode])
                         ->with('success', $statusPesan);
    }
}