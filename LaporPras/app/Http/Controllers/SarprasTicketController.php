<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SarprasTicketController extends Controller
{
    public function index()
    {
        $response = Http::get(env('ORACLE_ORDS_URL') . '/tiket/daftar');
        $tickets  = $response->json()['items'] ?? [];

        return view('sarpras.daftar_tiket', ['tickets' => $tickets]);
    }
    public function completeRepair(Request $request, $ticketId)
    {
        $request->validate([
            'photo_after' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'repair_cost' => 'required|numeric|min:0',
            'note'        => 'required|string|max:255',
        ]);

        // Simpan foto sesudah perbaikan ke storage Laravel
        $photoAfterPath = $request->file('photo_after')->store('tickets/after', 'public');

        // Kirim data ke REST API Oracle APEX (memanggil sp_selesaikan_perbaikan)
        $response = Http::post(env('ORACLE_ORDS_URL') . '/tiket/selesai', [
            'p_ticket_id'   => (int) $ticketId,
            'p_handled_by'  => (int) session('sarpras_user_id', 1),
            'p_photo_after' => $photoAfterPath,
            'p_repair_cost' => (float) $request->input('repair_cost'),
            'p_note'        => $request->input('note'),
        ]);

        $hasil = $response->json();
        $totalBiaya  = $hasil['p_total_biaya'] ?? 0;
        $statusPesan = $hasil['p_status_pesan'] ?? 'Gagal menyelesaikan tiket.';

        return view('sarpras.hasil_perbaikan', [
            'statusPesan' => $statusPesan,
            'totalBiaya'  => number_format($totalBiaya, 0, ',', '.'),
        ]);
    }
}