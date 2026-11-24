<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penyelesaian Perbaikan Fasilitas - Sarpras Admin</title>
    
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
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen py-6 px-4 sm:px-6">

    <div class="max-w-xl mx-auto space-y-4">
        
        <!-- Navigation Header -->
        <div class="flex items-center justify-between">
            <a href="{{ route('sarpras.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-2xs transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
            <span class="text-xs font-mono font-bold bg-blue-100 text-blue-800 px-2.5 py-1 rounded-lg border border-blue-200">
                UJI SP 2 (sp_selesaikan_perbaikan)
            </span>
        </div>

        <!-- Ticket Summary Card (Informasi Tiket yang sedang diperbaiki) -->
        @if(isset($ticket))
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-wrench"></i>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold">Tiket Ditangani</span>
                        <h2 class="text-sm font-extrabold text-slate-900 font-mono">{{ $ticket['ticket_code'] ?? ('#' . $ticketId) }}</h2>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border bg-amber-50 text-amber-700 border-amber-200">
                    {{ $ticket['priority'] ?? 'Sedang' }}
                </span>
            </div>

            <div>
                <h3 class="text-sm font-bold text-slate-900">{{ $ticket['title'] ?? '-' }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    <i class="fa-solid fa-location-dot text-emerald-600 text-[10px] mr-1"></i>
                    {{ $ticket['room_name'] ?? 'Ruangan' }} &bull; {{ $ticket['reporter_name'] ?? 'Pelapor' }}
                </p>
                <p class="text-xs text-slate-600 mt-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100 leading-relaxed">
                    {{ $ticket['description'] ?? '-' }}
                </p>
            </div>

            @if(!empty($ticket['photo_before']))
                <div class="pt-1">
                    <span class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Foto Bukti Kerusakan Sebelum Perbaikan:</span>
                    <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-100 max-h-40">
                        @php
                            $bUrl = str_starts_with($ticket['photo_before'], 'http') ? $ticket['photo_before'] : asset('storage/' . $ticket['photo_before']);
                        @endphp
                        <img src="{{ $bUrl }}" alt="Foto Kerusakan" class="w-full h-36 object-cover">
                    </div>
                </div>
            @endif
        </div>
        @endif

        <!-- Form Penyelesaian Perbaikan -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-md space-y-4">
            
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900">Form Tindakan & Biaya Perbaikan</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Data akan dikirim ke REST API Oracle APEX dan memicu stored procedure penyelesaian.
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3 rounded-xl text-xs space-y-1">
                    <div class="font-bold">Mohon periksa input:</div>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formSelesai" action="{{ route('sarpras.complete', $ticketId) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Biaya Perbaikan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Biaya Perbaikan / Pembelian Komponen (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 font-bold text-xs">
                            Rp
                        </span>
                        <input type="number" name="repair_cost" id="repair_cost" 
                            value="{{ old('repair_cost', 150000) }}" 
                            oninput="updateRupiahPreview(this.value)"
                            min="0" step="1000"
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm font-mono rounded-xl pl-10 pr-3.5 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                            required>
                    </div>
                    <p class="text-[11px] text-emerald-700 font-semibold mt-1">
                        Terbilang: <span id="rupiahPreview" class="font-bold">Rp 150.000</span>
                    </p>
                </div>

                <!-- Catatan Tindakan Perbaikan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Catatan Tindakan Teknisi <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="note" id="note" rows="3" 
                        class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none" 
                        placeholder="Uraikan tindakan perbaikan yang dilakukan (misal: pembersihan filter AC, penggantian pipa, dll)..." required>{{ old('note', 'Pembersihan saluran pembuangan AC dan pengisian freon R32.') }}</textarea>
                </div>

                <!-- Foto Sesudah Diperbaiki (Live Preview) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-semibold text-slate-700">
                            Foto Bukti Sesudah Perbaikan <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] text-slate-400">Maks. 4MB</span>
                    </div>

                    <input type="file" name="photo_after" id="photo_after" accept="image/*" class="hidden" required onchange="previewAfterImage(this)">

                    <label id="uploadAfterPrompt" for="photo_after" class="cursor-pointer border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-2xl p-5 flex flex-col items-center justify-center text-center bg-slate-50/70 hover:bg-blue-50/30 transition select-none">
                        <div class="w-11 h-11 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg mb-2">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-800">
                            Ambil Foto / Unggah Bukti Selesai
                        </p>
                        <p class="text-[10px] text-slate-400 mt-0.5">
                            Menunjukkan kondisi fasilitas yang telah rapi dan berfungsi normal
                        </p>
                    </label>

                    <!-- Preview After Image -->
                    <div id="previewAfterCard" class="hidden relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 shadow-inner">
                        <img id="previewAfterImg" src="#" alt="Preview Sesudah" class="w-full h-44 object-cover opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20 pointer-events-none"></div>
                        <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-white">
                            <div>
                                <span class="text-[10px] font-bold bg-blue-500 px-2 py-0.5 rounded text-white">Foto Perbaikan Siap</span>
                                <p id="previewAfterName" class="text-xs truncate max-w-[200px] mt-0.5">foto_sesudah.jpg</p>
                            </div>
                            <button type="button" onclick="clearAfterPhoto()" class="px-2.5 py-1 rounded-lg bg-rose-600 text-white text-xs font-bold transition">
                                Ganti
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="submitBtn" class="w-full py-3.5 px-4 rounded-xl font-bold text-sm text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/25 flex items-center justify-center gap-2 transition active:scale-98">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Simpan & Selesaikan Tiket Perbaikan</span>
                    </button>
                </div>

            </form>

        </div>

    </div>

    <!-- Script Form -->
    <script>
        function updateRupiahPreview(val) {
            const num = Number(val) || 0;
            document.getElementById('rupiahPreview').innerText = 'Rp ' + num.toLocaleString('id-ID');
        }

        function previewAfterImage(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewAfterImg').src = e.target.result;
                    document.getElementById('previewAfterName').innerText = file.name;
                    document.getElementById('uploadAfterPrompt').classList.add('hidden');
                    document.getElementById('previewAfterCard').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function clearAfterPhoto() {
            const input = document.getElementById('photo_after');
            input.value = '';
            document.getElementById('previewAfterImg').src = '#';
            document.getElementById('previewAfterCard').classList.add('hidden');
            document.getElementById('uploadAfterPrompt').classList.remove('hidden');
        }

        document.getElementById('formSelesai').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Menyimpan ke Oracle ORDS...';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>
</body>
</html>
