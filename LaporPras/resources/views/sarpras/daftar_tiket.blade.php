<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Sarpras - Daftar Tiket Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-6">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-xl font-bold">Dashboard Sarpras - Daftar Tiket Kerusakan</h1>
                <p class="text-sm text-slate-500">Data real-time dari Oracle Cloud (Tabel TICKETS)</p>
            </div>
            <a href="{{ route('public.form', 'smkn-4-bandung') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                + Buat Laporan Baru
            </a>
        </div>

        <table class="w-full border-collapse border border-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="border p-2 text-left">Kode Tiket</th>
                    <th class="border p-2 text-left">Pelapor</th>
                    <th class="border p-2 text-left">Ruangan / Aset</th>
                    <th class="border p-2 text-left">Judul Kerusakan</th>
                    <th class="border p-2 text-center">Prioritas</th>
                    <th class="border p-2 text-center">Status</th>
                    <th class="border p-2 text-right">Biaya</th>
                    <th class="border p-2 text-center">Aksi Sarpras</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $t)
                <tr>
                    <td class="border p-2 font-mono font-bold">{{ $t['ticket_code'] }}</td>
                    <td class="border p-2">{{ $t['reporter_name'] }} ({{ $t['reporter_info'] }})</td>
                    <td class="border p-2">{{ $t['room_name'] ?? '-' }} / {{ $t['asset_name'] ?? 'Non-Aset' }}</td>
                    <td class="border p-2">{{ $t['title'] }}</td>
                    <td class="border p-2 text-center">{{ $t['priority'] }}</td>
                    <td class="border p-2 text-center">
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $t['status'] === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $t['status'] }}
                        </span>
                    </td>
                    <td class="border p-2 text-right">Rp {{ number_format($t['repair_cost'] ?? 0, 0, ',', '.') }}</td>
                    <td class="border p-2 text-center">
                        @if($t['status'] !== 'Selesai')
                            <a href="{{ route('sarpras.form_selesai', $t['id']) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-blue-700">
                                Selesaikan
                            </a>
                        @else
                            <span class="text-slate-400 text-xs">Tuntas</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="border p-4 text-center text-slate-500">Belum ada tiket laporan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>