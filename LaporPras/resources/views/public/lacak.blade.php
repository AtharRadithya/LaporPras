<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Lacak Status Tiket Kerusakan - LaporPras</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen pb-12">

    <!-- Top App Bar -->
    <header class="bg-white sticky top-0 z-40 border-b border-slate-200 shadow-xs">
        <div class="max-w-xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition active:scale-95">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>

            <div class="text-center">
                <span class="text-[10px] font-extrabold tracking-widest uppercase text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                    Tracking Center
                </span>
                <h1 class="text-sm font-bold text-slate-900 leading-tight">Lacak Tiket Fasilitas</h1>
            </div>

            <a href="{{ route('public.form', 'smkn-4-bandung') }}" class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center transition active:scale-95" title="Buat Laporan Baru">
                <i class="fa-solid fa-plus text-sm"></i>
            </a>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-4 pt-4 sm:pt-6 space-y-4">

        <!-- Search Bar Tiket -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs">
            <form action="{{ route('public.track.search') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-ticket text-xs"></i>
                    </span>
                    <input type="text" name="code" value="{{ $code ?? '' }}" 
                        placeholder="Masukkan Kode Tiket (misal: TKT-2602-001)" 
                        class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-xs sm:text-sm rounded-xl pl-9 pr-3 py-2.5 font-mono uppercase focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                </div>
                <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs sm:text-sm font-bold flex items-center gap-1.5 shadow-sm transition active:scale-95">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Cari</span>
                </button>
            </form>
        </div>

        @if(session('success'))
            <!-- Success Notification Card for Newly Created Report -->
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 text-white shadow-lg text-center relative overflow-hidden">
                <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl mx-auto mb-3 shadow-inner">
                    <i class="fa-solid fa-check text-emerald-100"></i>
                </div>
                <span class="inline-block text-[11px] font-mono bg-white/20 px-2.5 py-0.5 rounded-full border border-white/20 mb-2">
                    Output: sp_buat_laporan_rusak
                </span>
                <h2 class="text-xl font-extrabold mb-1">Laporan Berhasil Terkirim!</h2>
                <p class="text-xs text-white/90 leading-relaxed max-w-sm mx-auto">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if($code)
            <!-- Ticket Code Box & Copy Button -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs text-center space-y-3">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Tiket Anda</span>
                <div class="flex items-center justify-center gap-2">
                    <div id="ticketCodeText" class="text-2xl sm:text-3xl font-mono font-extrabold text-slate-900 bg-slate-100 px-4 py-2 rounded-xl tracking-wider select-all border border-slate-200">
                        {{ $code }}
                    </div>
                    <button onclick="copyTicketCode()" id="copyBtn" class="h-12 px-3.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold flex items-center gap-1.5 transition active:scale-95" title="Salin Kode">
                        <i class="fa-regular fa-copy text-sm"></i>
                        <span id="copyBtnText" class="hidden sm:inline">Salin</span>
                    </button>
                </div>
                <p class="text-[11px] text-slate-500">
                    Simpan kode ini untuk mengecek progres penanganan kerusakan sewaktu-waktu.
                </p>

                <!-- WhatsApp Share Button -->
                <div class="pt-2">
                    <a href="https://api.whatsapp.com/send?text=Halo%2C%20saya%20sudah%20melaporkan%20kerusakan%20fasilitas%20sekolah%20dengan%20Kode%20Tiket%20*{{ $code }}*.%20Mohon%20segera%20ditindaklanjuti%20tim%20Sarpras.%20Terima%20kasih." target="_blank" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition shadow-sm">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Bagikan Kode ke WhatsApp Petugas</span>
                    </a>
                </div>
            </div>

            <!-- Progress Stepper Tracker -->
            @php
                $status = $ticket['status'] ?? 'Baru';
                $isCompleted = ($status === 'Selesai');
                $isProgress = in_array($status, ['Dalam Pengerjaan', 'Proses', 'Sedang Diproses']);
                $step = $isCompleted ? 4 : ($isProgress ? 3 : 2);
            @endphp
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 text-center">
                    Status Progres Penanganan
                </h3>

                <div class="relative">
                    <!-- Progress Bar Line -->
                    <div class="absolute top-4 left-6 right-6 h-1 bg-slate-100 -z-0">
                        <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ $step == 4 ? '100%' : ($step == 3 ? '66%' : ($step == 2 ? '33%' : '0%')) }}"></div>
                    </div>

                    <!-- Steps -->
                    <div class="grid grid-cols-4 relative z-10 text-center">
                        <!-- Step 1 -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold ring-4 ring-white shadow-xs">
                                <i class="fa-solid fa-check text-[11px]"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-800 mt-2">Terkirim</span>
                            <span class="text-[9px] text-slate-400 hidden sm:inline">Database</span>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $step >= 2 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-xs font-bold ring-4 ring-white shadow-xs">
                                <i class="fa-solid fa-user-check text-[11px]"></i>
                            </div>
                            <span class="text-[10px] font-bold {{ $step >= 2 ? 'text-slate-800' : 'text-slate-400' }} mt-2">Verifikasi</span>
                            <span class="text-[9px] text-slate-400 hidden sm:inline">Sarpras</span>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $step >= 3 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-xs font-bold ring-4 ring-white shadow-xs">
                                <i class="fa-solid fa-screwdriver-wrench text-[11px]"></i>
                            </div>
                            <span class="text-[10px] font-bold {{ $step >= 3 ? 'text-slate-800' : 'text-slate-400' }} mt-2">Pengerjaan</span>
                            <span class="text-[9px] text-slate-400 hidden sm:inline">Teknisi</span>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $step >= 4 ? 'bg-emerald-500 text-white ring-emerald-100' : 'bg-slate-200 text-slate-500 ring-white' }} flex items-center justify-center text-xs font-bold ring-4 shadow-xs">
                                <i class="fa-solid fa-flag-checkered text-[11px]"></i>
                            </div>
                            <span class="text-[10px] font-bold {{ $step >= 4 ? 'text-emerald-600' : 'text-slate-400' }} mt-2">Selesai</span>
                            <span class="text-[9px] text-slate-400 hidden sm:inline">Tuntas</span>
                        </div>
                    </div>
                </div>

                <!-- Status Badge Banner -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Status Saat Ini:</span>
                    <span class="px-3 py-1 rounded-full font-bold text-xs {{ $isCompleted ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                        <i class="fa-solid {{ $isCompleted ? 'fa-circle-check text-emerald-600' : 'fa-clock text-amber-600' }} mr-1"></i>
                        {{ $ticket['status'] ?? 'Menunggu Verifikasi' }}
                    </span>
                </div>
            </div>

            <!-- Ticket Detail Card -->
            @if($ticket)
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs space-y-3.5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h4 class="text-sm font-bold text-slate-900">Rincian Laporan Kerusakan</h4>
                        @php
                            $p = $ticket['priority'] ?? 'Sedang';
                            $pColors = [
                                'Darurat' => 'bg-rose-100 text-rose-800 border-rose-200',
                                'Tinggi'  => 'bg-orange-100 text-orange-800 border-orange-200',
                                'Sedang'  => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'Rendah'  => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            ];
                        @endphp
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full border {{ $pColors[$p] ?? 'bg-slate-100 text-slate-800 border-slate-200' }}">
                            Prioritas: {{ $p }}
                        </span>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-semibold">Judul Masalah</span>
                            <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $ticket['title'] ?? '-' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Ruangan / Lokasi</span>
                                <p class="font-semibold text-slate-800 mt-0.5">
                                    <i class="fa-solid fa-location-dot text-emerald-600 mr-1"></i>
                                    {{ $ticket['room_name'] ?? 'Ruang Sekolah' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Aset Terkait</span>
                                <p class="font-semibold text-slate-800 mt-0.5">
                                    <i class="fa-solid fa-box text-blue-500 mr-1"></i>
                                    {{ $ticket['asset_name'] ?? 'Non-Aset' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Nama Pelapor</span>
                                <p class="font-medium text-slate-700 mt-0.5">{{ $ticket['reporter_name'] ?? '-' }} ({{ $ticket['reporter_info'] ?? '-' }})</p>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Waktu Dilaporkan</span>
                                <p class="font-medium text-slate-700 mt-0.5">{{ $ticket['created_at'] ?? now()->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-slate-400 block text-[10px] uppercase font-semibold">Keterangan Kerusakan</span>
                            <p class="text-slate-600 mt-0.5 leading-relaxed bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                {{ $ticket['description'] ?? 'Tidak ada deskripsi tambahan.' }}
                            </p>
                        </div>

                        <!-- Before & After Photos -->
                        @if(!empty($ticket['photo_before']) || !empty($ticket['photo_after']))
                            <div class="pt-3 border-t border-slate-100">
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold mb-2">Dokumentasi Foto</span>
                                <div class="grid grid-cols-2 gap-2">
                                    @if(!empty($ticket['photo_before']))
                                        <div>
                                            <span class="text-[10px] font-bold text-slate-500 block mb-1">Sebelum Perbaikan:</span>
                                            <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-100 aspect-video">
                                                @php
                                                    $beforeUrl = str_starts_with($ticket['photo_before'], 'http') ? $ticket['photo_before'] : asset('storage/' . $ticket['photo_before']);
                                                @endphp
                                                <img src="{{ $beforeUrl }}" alt="Foto Kerusakan" class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty($ticket['photo_after']))
                                        <div>
                                            <span class="text-[10px] font-bold text-emerald-600 block mb-1">Sesudah Diperbaiki:</span>
                                            <div class="rounded-xl overflow-hidden border border-emerald-300 bg-emerald-50 aspect-video">
                                                @php
                                                    $afterUrl = str_starts_with($ticket['photo_after'], 'http') ? $ticket['photo_after'] : asset('storage/' . $ticket['photo_after']);
                                                @endphp
                                                <img src="{{ $afterUrl }}" alt="Foto Sesudah Perbaikan" class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if(!empty($ticket['note']))
                            <div class="pt-2">
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Catatan Teknisi Sarpras</span>
                                <p class="text-emerald-800 bg-emerald-50 border border-emerald-200 p-2.5 rounded-xl mt-1 font-medium">
                                    <i class="fa-solid fa-wrench mr-1 text-emerald-600"></i> {{ $ticket['note'] }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        @else
            <!-- Empty Search Prompt -->
            <div class="bg-white rounded-2xl p-8 border border-slate-200 text-center space-y-3">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-2xl mx-auto">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900">Lacak Tiket Kerusakan</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                    Ketik kode tiket yang Anda dapatkan saat melapor pada kolom pencarian di atas untuk melihat status terkini.
                </p>
            </div>
        @endif

        <!-- Action Links -->
        <div class="flex flex-col sm:flex-row gap-2 pt-2">
            <a href="{{ route('public.form', 'smkn-4-bandung') }}" class="flex-1 py-3 px-4 rounded-xl font-bold text-xs text-center text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition active:scale-98">
                <i class="fa-solid fa-plus mr-1"></i> Buat Laporan Kerusakan Baru
            </a>
            <a href="{{ route('home') }}" class="py-3 px-4 rounded-xl font-semibold text-xs text-center text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition active:scale-98">
                Kembali ke Beranda
            </a>
        </div>

    </main>

    <!-- Copy to Clipboard Toast Script -->
    <script>
        function copyTicketCode() {
            const codeText = document.getElementById('ticketCodeText').innerText.trim();
            navigator.clipboard.writeText(codeText).then(() => {
                const btn = document.getElementById('copyBtn');
                const btnText = document.getElementById('copyBtnText');
                btn.classList.remove('bg-slate-800', 'hover:bg-slate-900');
                btn.classList.add('bg-emerald-600', 'text-white');
                btn.innerHTML = '<i class="fa-solid fa-check text-sm"></i> <span class="hidden sm:inline">Tersalin!</span>';
                
                setTimeout(() => {
                    btn.classList.remove('bg-emerald-600');
                    btn.classList.add('bg-slate-800', 'hover:bg-slate-900');
                    btn.innerHTML = '<i class="fa-regular fa-copy text-sm"></i> <span id="copyBtnText" class="hidden sm:inline">Salin</span>';
                }, 2000);
            });
        }
    </script>
</body>
</html>
