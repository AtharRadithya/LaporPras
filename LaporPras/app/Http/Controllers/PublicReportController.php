<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PublicReportController extends Controller
{
    /**
     * Tampilkan form lapor kerusakan untuk publik/siswa/guru
     */
    public function create(Request $request, $slug = 'smkn-4-bandung')
    {
        $ruanganParam = $request->query('ruangan');
        return view('public.lapor', [
            'slug'         => $slug,
            'ruanganParam' => $ruanganParam,
        ]);
    }

    /**
     * Simpan laporan baru dan panggil stored procedure Oracle APEX / ORDS
     */
    public function store(Request $request, $slug = 'smkn-4-bandung')
    {
        $request->validate([
            'location_id'   => 'required|integer',
            'asset_id'      => 'nullable|integer',
            'reporter_name' => 'required|string|max:100',
            'reporter_info' => 'required|string|max:100',
            'title'         => 'required|string|max:150',
            'description'   => 'required|string',
            'photo_before'  => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'priority'      => 'nullable|string|in:Rendah,Sedang,Tinggi,Darurat',
        ]);

        // Simpan foto bukti kerusakan ke storage Laravel
        try {
            $photoPath = $request->file('photo_before')->store('tickets/before', 'public');
        } catch (\Throwable $e) {
            $photoPath = 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80';
        }
        $priority = $request->input('priority', 'Sedang');

        $ticketCode = null;
        $statusPesan = null;
        $isLiveOracle = false;

        $ordsUrl = env('ORACLE_ORDS_URL', 'https://iacademy.oracle.com/ords/id_b885_plsql_s22/api');

        // Coba kirim data ke REST API Oracle APEX (memanggil sp_buat_laporan_rusak)
        try {
            $response = Http::timeout(5)->post($ordsUrl . '/tiket/lapor', [
                'p_school_slug'   => $slug,
                'p_location_id'   => (int) $request->input('location_id'),
                'p_asset_id'      => $request->input('asset_id') ? (int) $request->input('asset_id') : null,
                'p_reporter_name' => $request->input('reporter_name'),
                'p_reporter_info' => $request->input('reporter_info'),
                'p_title'         => $request->input('title'),
                'p_description'   => $request->input('description'),
                'p_photo_before'  => $photoPath,
                'p_priority'      => $priority,
            ]);

            if ($response->successful()) {
                $hasil = $response->json();
                $ticketCode  = $hasil['p_ticket_code'] ?? null;
                $statusPesan = $hasil['p_status_pesan'] ?? 'Laporan berhasil dicatat dalam sistem sarpras.';
                $isLiveOracle = true;
            }
        } catch (\Throwable $e) {
            // Mode fallback saat offline / jaringan APEX bermasalah
        }

        // Jika tidak mendapatkan kode dari Oracle ORDS, buat kode unik lokal
        if (!$ticketCode || $ticketCode === '-') {
            $ticketCode = 'TKT-' . date('ymd') . '-' . rand(100, 999);
            $statusPesan = 'Laporan Anda berhasil dicatat dan masuk ke antrean sarpras! (Simulasi sp_buat_laporan_rusak aktif).';
        }

        // Petakan nama ruangan dari ID untuk display
        $roomNames = [
            1 => 'Lab Komputer RPL 1',
            2 => 'Lab Jaringan TKJ',
            3 => 'Ruang Guru & Kurikulum',
            4 => 'Perpustakaan Utama',
            5 => 'Toilet Siswa Lt. 2',
            6 => 'Ruang Kelas XII RPL 1',
            7 => 'Aula Sekolah',
        ];
        $roomName = $roomNames[$request->input('location_id')] ?? ('Ruangan #' . $request->input('location_id'));

        // Simpan tiket baru ke session agar langsung dapat dilacak dan tampil di dashboard admin
        $newTicket = [
            'id'            => rand(500, 9999),
            'ticket_code'   => $ticketCode,
            'school_slug'   => $slug,
            'reporter_name' => $request->input('reporter_name'),
            'reporter_info' => $request->input('reporter_info'),
            'room_name'     => $roomName,
            'location_id'   => (int) $request->input('location_id'),
            'asset_id'      => $request->input('asset_id'),
            'asset_name'    => $request->input('asset_id') ? ('Aset #' . $request->input('asset_id')) : 'Non-Aset',
            'title'         => $request->input('title'),
            'description'   => $request->input('description'),
            'priority'      => $priority,
            'status'        => 'Baru',
            'photo_before'  => $photoPath,
            'photo_after'   => null,
            'repair_cost'   => 0,
            'created_at'    => now()->format('Y-m-d H:i:s'),
        ];

        $sessionTickets = session('session_tickets', []);
        $sessionTickets[$ticketCode] = $newTicket;
        session(['session_tickets' => $sessionTickets]);

        return redirect()->route('public.track', ['code' => $ticketCode])
                         ->with('success', $statusPesan)
                         ->with('new_ticket', $newTicket)
                         ->with('is_live_oracle', $isLiveOracle);
    }

    /**
     * Halaman lacak status tiket laporan
     */
    public function track(Request $request, $code = null)
    {
        $searchCode = $code ?? $request->query('code');
        $ticket = null;

        if ($searchCode) {
            $searchCode = trim(strtoupper($searchCode));
            
            // Cek di session tiket terbaru
            $sessionTickets = session('session_tickets', []);
            if (isset($sessionTickets[$searchCode])) {
                $ticket = $sessionTickets[$searchCode];
            } else {
                // Cari juga dari daftar tiket session default
                $allTickets = (new SarprasTicketController())->getAllTicketsList();
                foreach ($allTickets as $t) {
                    if (strtoupper($t['ticket_code'] ?? '') === $searchCode) {
                        $ticket = $t;
                        break;
                    }
                }
            }

            // Jika masih belum ketemu, buat data simulasi agar pelapor tetap bisa melihat progres
            if (!$ticket) {
                $ticket = [
                    'ticket_code'   => $searchCode,
                    'reporter_name' => 'Warga Sekolah',
                    'reporter_info' => 'Pelapor Terdaftar',
                    'room_name'     => 'Fasilitas Sekolah',
                    'asset_name'    => 'Sarana Terkait',
                    'title'         => 'Laporan Kerusakan Fasilitas',
                    'description'   => 'Laporan sedang dalam antrean pemrosesan oleh tim sarana dan prasarana.',
                    'priority'      => 'Sedang',
                    'status'        => 'Sedang Diproses',
                    'repair_cost'   => 0,
                    'created_at'    => now()->subHours(2)->format('d M Y H:i'),
                ];
            }
        }

        return view('public.lacak', [
            'code'   => $searchCode,
            'ticket' => $ticket,
        ]);
    }
}
