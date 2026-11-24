<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Penyelesaian Perbaikan - Sarpras</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen py-10 px-4">

    <div class="max-w-lg mx-auto space-y-4">
        
        <!-- Receipt Card Container -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl text-center space-y-4 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-blue-500"></div>

            <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mx-auto shadow-inner">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div>
                <span class="inline-block text-[11px] font-mono font-bold bg-blue-100 text-blue-800 px-3 py-1 rounded-full border border-blue-200 mb-2">
                    Output: sp_selesaikan_perbaikan
                </span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Perbaikan Berhasil Diselesaikan!</h1>
                <p class="text-xs text-slate-500 mt-1">Status tiket telah diperbarui menjadi Tuntas/Selesai di sistem.</p>
            </div>

            <!-- Pesan Stored Procedure -->
            <div class="bg-slate-50 border border-slate-200 text-slate-700 p-3.5 rounded-2xl text-xs leading-relaxed">
                {{ $statusPesan }}
            </div>

            <!-- Breakdown Detail Biaya -->
            <div class="grid grid-cols-2 gap-3 text-left">
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Biaya Tiket Ini:</span>
                    <p class="text-base font-extrabold text-slate-900 font-mono mt-0.5">Rp {{ $repairCost ?? '0' }}</p>
                </div>
                <div class="bg-emerald-50 p-3.5 rounded-xl border border-emerald-200">
                    <span class="text-[10px] text-emerald-700 font-bold uppercase block">Akumulasi Biaya Sekolah:</span>
                    <p class="text-base font-extrabold text-emerald-900 font-mono mt-0.5">Rp {{ $totalBiaya }}</p>
                </div>
            </div>

            <!-- Stored Function Highlight Banner -->
            <div class="bg-gradient-to-br from-emerald-900 to-teal-950 p-4 rounded-2xl text-white text-left shadow-md">
                <div class="flex items-center justify-between text-xs text-emerald-300 font-mono mb-1">
                    <span>STORED FUNCTION:</span>
                    <span>sf_total_biaya_perbaikan</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="text-xs text-white/80">Total Pengeluaran Sarpras:</span>
                    <span class="text-xl font-bold font-mono text-emerald-400">Rp {{ $totalBiaya }}</span>
                </div>
            </div>

            @if(!empty($note))
                <div class="text-left text-xs bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Catatan Tindakan:</span>
                    <p class="text-slate-700">{{ $note }}</p>
                </div>
            @endif

            @if(!empty($photoAfter))
                <div class="text-left">
                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Foto Bukti Hasil Perbaikan:</span>
                    <div class="rounded-xl overflow-hidden border border-slate-200 max-h-44">
                        <img src="{{ asset('storage/' . $photoAfter) }}" alt="Foto Sesudah Perbaikan" class="w-full h-36 object-cover">
                    </div>
                </div>
            @endif

            <!-- Navigation Buttons -->
            <div class="pt-2 flex flex-col sm:flex-row gap-2">
                <a href="{{ route('sarpras.index') }}" class="flex-1 py-3 px-4 rounded-xl font-bold text-xs text-white bg-slate-900 hover:bg-slate-800 shadow-md transition active:scale-98 flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-table-list"></i>
                    <span>Kembali ke Dashboard Sarpras</span>
                </a>
                <button onclick="window.print()" class="py-3 px-4 rounded-xl font-bold text-xs text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 transition active:scale-98 flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Bukti</span>
                </button>
            </div>

        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-slate-600 transition">
                &larr; Kembali ke Beranda Utama
            </a>
        </div>

    </div>

</body>
</html>
