<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Form Pelaporan Fasilitas Rusak - LaporPras</title>
    
    <!-- Fonts & Lucide Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --brand-green: #0F5132;
            --brand-green-hover: #0b3d26;
            --brand-green-subtle: #f0fdf4;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #fafafa;
            color: #1e293b;
            -webkit-tap-highlight-color: transparent;
        }

        .border-brand {
            border-color: var(--brand-green);
        }

        .bg-brand {
            background-color: var(--brand-green);
        }

        .bg-brand:hover {
            background-color: var(--brand-green-hover);
        }

        .text-brand {
            color: var(--brand-green);
        }

        .bg-brand-subtle {
            background-color: var(--brand-green-subtle);
        }

        /* Pure text chip styling */
        .select-chip {
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            color: #475569;
            transition: all 0.15s ease;
        }

        .select-chip.active {
            border-color: var(--brand-green);
            background-color: var(--brand-green-subtle);
            color: var(--brand-green);
            font-weight: 600;
        }

        /* Priority card checked styling */
        .priority-radio:checked + label {
            border-color: var(--brand-green);
            border-width: 2px;
            background-color: var(--brand-green-subtle);
            color: var(--brand-green);
        }

        .priority-radio:checked + label .priority-title {
            color: var(--brand-green);
            font-weight: 700;
        }

        /* Camera live preview aspect ratio */
        .camera-viewport {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-radius: 1rem;
            background-color: #0f172a;
        }
    </style>
</head>
<body class="min-h-screen antialiased flex flex-col justify-between pb-28 md:pb-12">

    <!-- Top App Bar (Clean, Minimalist, Material You) -->
    <header class="bg-white sticky top-0 z-40 border-b border-slate-200">
        <div class="max-w-xl mx-auto px-4 py-3.5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="p-2 -ml-2 rounded-full text-slate-700 hover:bg-slate-100 transition active:scale-95" aria-label="Kembali ke Beranda">
                <i data-lucide="arrow-left" class="w-5 h-5 stroke-[1.75]"></i>
            </a>

            <div class="text-center">
                <h1 class="text-sm font-bold text-slate-900 leading-tight">Lapor Fasilitas Rusak</h1>
                <p class="text-[11px] text-slate-500 font-medium">SMK Negeri 4 Bandung</p>
            </div>

            <a href="{{ route('public.track.search') }}" class="p-2 -mr-2 rounded-full text-slate-700 hover:bg-slate-100 transition active:scale-95" aria-label="Lacak Tiket Laporan" title="Lacak Tiket">
                <i data-lucide="search" class="w-5 h-5 stroke-[1.75]"></i>
            </a>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-4 pt-4 sm:pt-6 w-full flex-grow">
        
        <!-- Header Identitas Sekolah (Minimalis, Tanpa Grafis Generik) -->
        <div class="border border-slate-200 bg-white rounded-2xl p-4 mb-4 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Unit Layanan Pengaduan</p>
                <h2 class="text-base font-bold text-slate-900 leading-snug">{{ ucwords(str_replace('-', ' ', $slug)) }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Penanganan fasilitas dan sarana prasarana sekolah</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center text-[10px] font-mono px-2 py-1 rounded bg-slate-100 text-slate-600 border border-slate-200">
                    QR-READY
                </span>
            </div>
        </div>

        <!-- Banner Anti-Duplikasi (Laporan Aktif di Ruangan Ini) -->
        <div id="activeReportBanner" class="hidden mb-4 p-3.5 rounded-xl border border-amber-200 bg-amber-50/80 text-slate-800 flex items-center justify-between gap-3 text-xs">
            <div class="flex items-start gap-2.5">
                <i data-lucide="alert-circle" class="w-4 h-4 text-amber-700 stroke-[1.75] shrink-0 mt-0.5"></i>
                <div>
                    <span id="activeReportText" class="font-medium text-amber-950 block">Laporan aktif di ruangan ini: AC Bocor (Sedang Diproses)</span>
                    <span class="text-[10px] text-amber-700 block mt-0.5">Sudah dilaporkan oleh warga sekolah lain. Anda dapat ikut mendukung agar segera ditangani.</span>
                </div>
            </div>
            <button type="button" id="btnIkutLapor" onclick="tambahDukungan()" class="text-xs font-semibold px-2.5 py-1.5 rounded-lg bg-white border border-amber-300 text-amber-900 hover:bg-amber-100 transition active:scale-95 shrink-0 whitespace-nowrap">
                Ikut Lapor (+1)
            </button>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 p-3.5 rounded-xl text-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5 mb-1">
                    <i data-lucide="alert-circle" class="w-4 h-4 stroke-[1.75] text-rose-600"></i>
                    <span>Harap lengkapi formulir:</span>
                </div>
                <ul class="list-disc pl-5 space-y-0.5 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulir Laporan Kerusakan -->
        <form id="laporForm" action="{{ route('public.store', $slug) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Hidden Technical Fields (ID Ruangan & Aset disimpan tanpa membingungkan pelapor) -->
            <input type="hidden" name="location_id" id="location_id" value="1">
            <input type="hidden" name="asset_id" id="asset_id" value="">

            <!-- LANGKAH 1: Data Diri Pelapor -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 space-y-3.5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Langkah 1 &bull; Data Diri Pelapor
                    </h3>
                    <span class="text-[11px] text-slate-400">Wajib diisi</span>
                </div>

                <div>
                    <label for="reporter_name" class="block text-xs font-medium text-slate-700 mb-1.5">
                        Nama Lengkap
                    </label>
                    <input type="text" name="reporter_name" id="reporter_name" 
                        value="{{ old('reporter_name', 'Kennan - XII RPL 1') }}" 
                        class="w-full bg-slate-50/70 border border-slate-200 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition" 
                        placeholder="Ketik nama lengkap Anda" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">
                        Status / Peran di Sekolah
                    </label>

                    <!-- Pilihan Peran Bersih (Tanpa Emoji & Tanpa Ikon di Chip) -->
                    <div class="grid grid-cols-4 gap-2" id="roleChipsContainer">
                        <button type="button" onclick="selectRole('Siswa', this)" class="select-chip role-chip active text-xs py-2 px-2.5 rounded-xl font-medium text-center">
                            Siswa
                        </button>
                        <button type="button" onclick="selectRole('Guru', this)" class="select-chip role-chip text-xs py-2 px-2.5 rounded-xl font-medium text-center">
                            Guru
                        </button>
                        <button type="button" onclick="selectRole('Staf', this)" class="select-chip role-chip text-xs py-2 px-2.5 rounded-xl font-medium text-center">
                            Staf
                        </button>
                        <button type="button" onclick="selectRole('Lainnya', this)" class="select-chip role-chip text-xs py-2 px-2.5 rounded-xl font-medium text-center">
                            Lainnya
                        </button>
                    </div>

                    <!-- Hidden Input yang dikirim ke controller -->
                    <input type="hidden" name="reporter_info" id="reporter_info" value="{{ old('reporter_info', 'Siswa') }}">

                    <!-- Input Tambahan (HANYA MUNCUL KONDISIONAL JIKA MEMILIH 'LAINNYA') -->
                    <div id="customRoleWrapper" class="hidden mt-2.5 pt-2 border-t border-slate-100">
                        <label for="custom_role_text" class="block text-[11px] font-medium text-slate-600 mb-1">
                            Sebutkan Peran / Jabatan Anda
                        </label>
                        <input type="text" id="custom_role_text" oninput="updateCustomRole(this.value)" 
                            class="w-full bg-slate-50/70 border border-slate-200 text-slate-900 text-xs rounded-xl px-3 py-2 focus:bg-white focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition" 
                            placeholder="Contoh: Orang Tua Siswa / Tamu Dinas / Tenaga Kebersihan">
                    </div>
                </div>
            </div>

            <!-- LANGKAH 2: Lokasi Ruangan (Dukungan QR Code & Pilihan Barang Dinamis) -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 space-y-3.5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Langkah 2 &bull; Lokasi & Fasilitas
                    </h3>
                    <span id="qrStatusBadge" class="hidden text-[10px] font-medium px-2 py-0.5 rounded bg-brand-subtle text-brand border border-brand">
                        Dari QR Code
                    </span>
                </div>

                <!-- Tampilan Terkunci Jika Ada Parameter URL ?ruangan=ID (Hasil Scan QR Pintu) -->
                <div id="lockedLocationCard" class="hidden p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block">Ruangan Terpilih</span>
                        <p id="lockedLocationName" class="text-sm font-bold text-slate-900 mt-0.5">Lab Komputer RPL 1</p>
                    </div>
                    <button type="button" onclick="unlockRoomSelection()" class="text-xs font-medium text-brand underline hover:opacity-80 py-1 px-2">
                        Ubah
                    </button>
                </div>

                <!-- Daftar Chip Pilihan Ruangan Manual (Tampil jika ruangan belum terkunci) -->
                <div id="roomSelectorWrapper">
                    <label class="block text-xs font-medium text-slate-700 mb-2">
                        Pilih Ruangan
                    </label>

                    <!-- Chip Ruangan Bersih Monokrom (Tanpa Ikon & Tanpa Emoji) -->
                    <div class="flex flex-wrap gap-2" id="roomChipsList">
                        <!-- Diisi secara dinamis oleh JavaScript -->
                    </div>
                </div>

                <!-- Sub-Bagian: Pilihan Barang Dinamis di Ruangan Ini -->
                <div id="itemSelectorWrapper" class="pt-3 border-t border-slate-100 space-y-2">
                    <div class="flex items-baseline justify-between">
                        <label class="block text-xs font-medium text-slate-700">
                            Barang di Ruangan Ini
                        </label>
                        <span class="text-[10px] text-slate-400">Pilih untuk isi judul otomatis</span>
                    </div>

                    <!-- Chip Barang Bersih (Tanpa Ikon & Tanpa Emoji) -->
                    <div class="flex flex-wrap gap-2" id="itemChipsList">
                        <!-- Diisi secara dinamis berdasarkan ruangan terpilih -->
                    </div>
                </div>
            </div>

            <!-- LANGKAH 3: Rincian Kerusakan & Urgensi -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 space-y-3.5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Langkah 3 &bull; Rincian Kerusakan
                    </h3>
                </div>

                <div>
                    <label for="title" class="block text-xs font-medium text-slate-700 mb-1.5">
                        Judul Kerusakan
                    </label>
                    <input type="text" name="title" id="title" 
                        value="{{ old('title') }}" 
                        class="w-full bg-slate-50/70 border border-slate-200 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition font-medium" 
                        placeholder="Contoh: AC Lab RPL 1 - Bocor dan menetes ke meja" required>
                </div>

                <div>
                    <label for="description" class="block text-xs font-medium text-slate-700 mb-1.5">
                        Deskripsi Kerusakan
                    </label>
                    <textarea name="description" id="description" rows="3" 
                        class="w-full bg-slate-50/70 border border-slate-200 text-slate-900 text-sm rounded-xl px-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition resize-none" 
                        placeholder="Tuliskan posisi detail kerusakan dan kendala yang dialami secara singkat..." required>{{ old('description', 'Air menetes dari bagian indoor unit sebelah papan tulis, mengenai meja siswa.') }}</textarea>
                </div>

                <!-- Tingkat Urgensi (4 Grid Bersih Tanpa Emoji / Tanpa Titik Warna) -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-2">
                        Tingkat Urgensi
                    </label>

                    <div class="grid grid-cols-2 gap-2">
                        <!-- Rendah -->
                        <div>
                            <input type="radio" name="priority" id="priority_rendah" value="Rendah" class="priority-radio hidden" {{ old('priority') == 'Rendah' ? 'checked' : '' }}>
                            <label for="priority_rendah" class="cursor-pointer flex flex-col p-3 rounded-xl border border-slate-200 hover:border-slate-300 transition select-none">
                                <span class="priority-title text-xs font-semibold text-slate-800">Rendah</span>
                                <span class="text-[10px] text-slate-500 mt-0.5">Tidak mengganggu KBM</span>
                            </label>
                        </div>

                        <!-- Sedang -->
                        <div>
                            <input type="radio" name="priority" id="priority_sedang" value="Sedang" class="priority-radio hidden" {{ old('priority') == 'Sedang' ? 'checked' : '' }}>
                            <label for="priority_sedang" class="cursor-pointer flex flex-col p-3 rounded-xl border border-slate-200 hover:border-slate-300 transition select-none">
                                <span class="priority-title text-xs font-semibold text-slate-800">Sedang</span>
                                <span class="text-[10px] text-slate-500 mt-0.5">Mengurangi kenyamanan</span>
                            </label>
                        </div>

                        <!-- Tinggi -->
                        <div>
                            <input type="radio" name="priority" id="priority_tinggi" value="Tinggi" class="priority-radio hidden" {{ old('priority', 'Tinggi') == 'Tinggi' ? 'checked' : '' }}>
                            <label for="priority_tinggi" class="cursor-pointer flex flex-col p-3 rounded-xl border border-slate-200 hover:border-slate-300 transition select-none">
                                <span class="priority-title text-xs font-semibold text-slate-800">Tinggi</span>
                                <span class="text-[10px] text-slate-500 mt-0.5">Menghambat KBM</span>
                            </label>
                        </div>

                        <!-- Darurat -->
                        <div>
                            <input type="radio" name="priority" id="priority_darurat" value="Darurat" class="priority-radio hidden" {{ old('priority') == 'Darurat' ? 'checked' : '' }}>
                            <label for="priority_darurat" class="cursor-pointer flex flex-col p-3 rounded-xl border border-slate-200 hover:border-slate-300 transition select-none">
                                <span class="priority-title text-xs font-semibold text-slate-800">Darurat</span>
                                <span class="text-[10px] text-slate-500 mt-0.5">Bahaya keamanan / listrik</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LANGKAH 4: Wajib Foto Langsung Dari Kamera (Blokir Galeri) -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 space-y-3.5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Langkah 4 &bull; Foto Bukti Kerusakan
                    </h3>
                    <span class="text-[11px] text-slate-400">Wajib kamera</span>
                </div>

                <!-- Input File dengan Atribut capture="environment" (Memicu Kamera Belakang Ponsel, Blokir Galeri) -->
                <input type="file" name="photo_before" id="photo_before" 
                    accept="image/*" 
                    capture="environment" 
                    class="hidden" 
                    required 
                    onchange="handleNativeCapture(this)">

                <!-- Tombol Utama Pemicu Kamera -->
                <div id="cameraPromptCard">
                    <button type="button" onclick="triggerCameraCapture()" class="w-full border border-dashed border-slate-300 hover:border-brand rounded-2xl p-6 flex flex-col items-center justify-center text-center bg-slate-50/50 hover:bg-brand-subtle/40 transition active:scale-[0.99] select-none">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-brand flex items-center justify-center mb-3">
                            <i data-lucide="camera" class="w-6 h-6 stroke-[1.75]"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-900">
                            Ketuk untuk Buka Kamera & Ambil Foto Bukti
                        </p>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Wajib foto langsung di lokasi (Maks. 4MB)
                        </p>
                    </button>
                </div>

                <!-- Live Camera Viewport Container (HTML5 getUserMedia Video Stream) -->
                <div id="liveStreamContainer" class="hidden space-y-2">
                    <div class="relative rounded-2xl overflow-hidden bg-black">
                        <video id="cameraVideo" autoplay playsinline class="camera-viewport"></video>
                        <div class="absolute bottom-3 inset-x-0 flex justify-center items-center gap-3 z-20">
                            <button type="button" onclick="captureLiveSnapshot()" class="px-5 py-2.5 bg-brand text-white font-semibold rounded-full shadow text-xs flex items-center gap-2 active:scale-95">
                                <i data-lucide="camera" class="w-4 h-4 stroke-[1.75]"></i>
                                <span>Jepret Foto</span>
                            </button>
                            <button type="button" onclick="closeLiveCamera()" class="p-2.5 bg-white/20 hover:bg-white/30 text-white rounded-full text-xs active:scale-95" title="Batal">
                                <i data-lucide="x" class="w-4 h-4 stroke-[1.75]"></i>
                            </button>
                        </div>
                    </div>
                    <canvas id="snapshotCanvas" class="hidden"></canvas>
                </div>

                <!-- Pratinjau Foto Hasil Jepretan Kamera Lengkap dengan Tombol "Foto Ulang" -->
                <div id="photoPreviewContainer" class="hidden space-y-2">
                    <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-900">
                        <img id="previewImageElement" src="#" alt="Foto Bukti Kerusakan" class="w-full h-52 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent pointer-events-none"></div>
                        
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white">
                            <div>
                                <span class="text-[10px] font-semibold tracking-wider uppercase text-emerald-300">Foto Siap Terlampir</span>
                                <p id="photoMetaInfo" class="text-xs text-white/90 truncate max-w-[200px]">foto_kamera.jpg</p>
                            </div>
                            <button type="button" onclick="retakePhoto()" class="px-3 py-1.5 rounded-xl bg-white text-slate-900 text-xs font-bold flex items-center gap-1.5 shadow-sm active:scale-95 transition">
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5 stroke-[1.75]"></i>
                                <span>Foto Ulang</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tombol Utama Kirim Laporan Kerusakan (Sticky Bottom di Mobile) -->
            <div class="fixed bottom-0 left-0 right-0 p-3 sm:p-4 bg-white/95 backdrop-blur-md border-t border-slate-200 z-30 md:static md:p-0 md:bg-transparent md:border-0 md:backdrop-blur-none">
                <div class="max-w-xl mx-auto">
                    <button type="submit" id="submitBtn" class="w-full py-3.5 px-4 rounded-xl font-bold text-sm text-white bg-brand hover:bg-[#0b3d26] shadow-sm flex items-center justify-center gap-2 transition active:scale-[0.99]">
                        <i data-lucide="send" class="w-4 h-4 stroke-[1.75]"></i>
                        <span>Kirim Laporan Kerusakan</span>
                    </button>
                    <p class="text-center text-[10px] text-slate-400 mt-1 md:hidden">
                        Data langsung diteruskan ke tim Sarpras SMK Negeri 4 Bandung
                    </p>
                </div>
            </div>

        </form>

        <!-- Tautan Lacak Tiket Anda Di Sini -->
        <div class="mt-8 text-center pb-6">
            <p class="text-xs text-slate-500">
                Sudah pernah melapor sebelumnya?
                <a href="{{ route('public.track.search') }}" class="font-bold text-brand hover:underline ml-1">
                    Lacak Tiket Anda Di Sini
                </a>
            </p>
        </div>

    </main>

    <!-- Script Logika: QR Code Parameter, Pilihan Barang, Anti-Duplikasi & Kamera Langsung -->
    <script>
        // Database Ruangan, Barang, dan Laporan Aktif
        const roomDatabase = {
            1: {
                id: 1,
                aliases: ['1', 'rpl1', 'lab-rpl-1', 'lab-komputer-rpl-1'],
                name: 'Lab Komputer RPL 1',
                activeReport: 'AC Bocor (Sedang Diproses)',
                items: ['AC', 'PC Siswa', 'Monitor', 'Proyektor', 'Keyboard & Mouse', 'Kursi Lab', 'Stopkontak', 'Lampu', 'Lainnya']
            },
            2: {
                id: 2,
                aliases: ['2', 'tkj', 'lab-tkj', 'lab-jaringan-tkj'],
                name: 'Lab Jaringan TKJ',
                activeReport: 'Kabel Patch Panel Kendor (Sedang Diproses)',
                items: ['Switch Hub', 'Router Mikrotik', 'Kabel LAN', 'AC', 'Proyektor', 'PC Siswa', 'Lampu', 'Lainnya']
            },
            3: {
                id: 3,
                aliases: ['3', 'guru', 'ruang-guru'],
                name: 'Ruang Guru & Kurikulum',
                activeReport: 'Roda Kursi Kerja Patah (Menunggu Pengecekan)',
                items: ['AC', 'Kursi Kerja', 'Meja Guru', 'Printer', 'Stopkontak', 'Lampu', 'Pintu / Jendela', 'Lainnya']
            },
            4: {
                id: 4,
                aliases: ['4', 'perpus', 'perpustakaan'],
                name: 'Perpustakaan Utama',
                activeReport: null,
                items: ['AC', 'Rak Buku', 'Meja Baca', 'Kursi', 'Komputer Katalog', 'Lampu', 'Lainnya']
            },
            5: {
                id: 5,
                aliases: ['5', 'toilet', 'toilet-lt-2'],
                name: 'Toilet Siswa Lt. 2',
                activeReport: 'Keran Wastafel Patah (Menunggu Suku Cadang)',
                items: ['Keran Air', 'Wastafel', 'Kloset', 'Pintu Kamar Mandi', 'Lampu', 'Saluran Air', 'Lainnya']
            },
            6: {
                id: 6,
                aliases: ['6', 'xii-rpl-1', 'kelas-xii-rpl-1'],
                name: 'Ruang Kelas XII RPL 1',
                activeReport: 'Proyektor Mati Total (Dalam Pengerjaan)',
                items: ['Proyektor', 'Papan Tulis', 'Meja Siswa', 'Kursi Siswa', 'Kipas Angin', 'Lampu', 'Stopkontak', 'Lainnya']
            },
            7: {
                id: 7,
                aliases: ['7', 'aula'],
                name: 'Aula Sekolah',
                activeReport: null,
                items: ['Sound System', 'Mic Wireless', 'Proyektor Besar', 'Lampu Sorot', 'AC Sentral', 'Kursi Lipat', 'Panggung', 'Lainnya']
            }
        };

        let currentSelectedRoomId = 1;
        let activeMediaStream = null;
        let counterDukungan = 1;

        // Inisialisasi Saat Halaman Dimuat
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi ikon Lucide
            lucide.createIcons();

            // Render daftar chip ruangan manual
            renderRoomChips();

            // Periksa Query Parameter ?ruangan=ID dari URL
            const urlParams = new URLSearchParams(window.location.search);
            const ruanganQuery = urlParams.get('ruangan') || "{{ $ruanganParam ?? '' }}";

            if (ruanganQuery) {
                // Temukan ruangan berdasarkan ID atau alias slug
                const matchedRoom = findRoomByQuery(ruanganQuery);
                if (matchedRoom) {
                    lockRoomTo(matchedRoom.id);
                } else {
                    selectRoom(1, false);
                }
            } else {
                selectRoom(1, false);
            }
        });

        // 1. Logika Pemilihan Peran (Langkah 1)
        function selectRole(role, buttonElement) {
            document.getElementById('reporter_info').value = role;
            
            document.querySelectorAll('.role-chip').forEach(btn => {
                btn.classList.remove('active');
            });
            buttonElement.classList.add('active');

            const customWrapper = document.getElementById('customRoleWrapper');
            const customInput = document.getElementById('custom_role_text');
            if (role === 'Lainnya') {
                customWrapper.classList.remove('hidden');
                customInput.focus();
                if (customInput.value.trim() !== '') {
                    document.getElementById('reporter_info').value = customInput.value.trim();
                }
            } else {
                customWrapper.classList.add('hidden');
            }
        }

        function updateCustomRole(val) {
            document.getElementById('reporter_info').value = val.trim() || 'Lainnya';
        }

        // 2. Logika Ruangan & QR Code Parameter (Langkah 2)
        function findRoomByQuery(query) {
            const clean = String(query).toLowerCase().trim();
            for (const key in roomDatabase) {
                const room = roomDatabase[key];
                if (String(room.id) === clean || room.aliases.includes(clean)) {
                    return room;
                }
            }
            return null;
        }

        function renderRoomChips() {
            const container = document.getElementById('roomChipsList');
            container.innerHTML = '';

            for (const key in roomDatabase) {
                const room = roomDatabase[key];
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `select-chip room-chip text-xs py-2 px-3 rounded-xl font-medium text-left transition select-none ${room.id === currentSelectedRoomId ? 'active' : ''}`;
                btn.innerText = room.name;
                btn.onclick = () => selectRoom(room.id, true);
                btn.id = `room-chip-${room.id}`;
                container.appendChild(btn);
            }
        }

        function lockRoomTo(roomId) {
            const room = roomDatabase[roomId] || roomDatabase[1];
            currentSelectedRoomId = room.id;
            document.getElementById('location_id').value = room.id;

            // Kunci tampilan ruangan (Mode QR Pintu)
            document.getElementById('lockedLocationCard').classList.remove('hidden');
            document.getElementById('lockedLocationName').innerText = room.name;
            document.getElementById('qrStatusBadge').classList.remove('hidden');
            document.getElementById('roomSelectorWrapper').classList.add('hidden');

            updateRoomItems(room);
            checkActiveReportBanner(room);
        }

        function unlockRoomSelection() {
            document.getElementById('lockedLocationCard').classList.add('hidden');
            document.getElementById('qrStatusBadge').classList.add('hidden');
            document.getElementById('roomSelectorWrapper').classList.remove('hidden');
            renderRoomChips();
        }

        function selectRoom(roomId, updateItemsNow = true) {
            const room = roomDatabase[roomId] || roomDatabase[1];
            currentSelectedRoomId = room.id;
            document.getElementById('location_id').value = room.id;

            document.querySelectorAll('.room-chip').forEach(btn => {
                btn.classList.remove('active');
            });
            const activeBtn = document.getElementById(`room-chip-${room.id}`);
            if (activeBtn) activeBtn.classList.add('active');

            if (updateItemsNow) {
                updateRoomItems(room);
                checkActiveReportBanner(room);
            }
        }

        // 3. Logika Chip Barang Dinamis & Otomatis Isi Judul Kerusakan
        function updateRoomItems(room) {
            const container = document.getElementById('itemChipsList');
            container.innerHTML = '';

            room.items.forEach(itemName => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'select-chip item-chip text-xs py-1.5 px-3 rounded-xl font-medium transition select-none';
                btn.innerText = itemName;
                btn.onclick = () => selectItemForTitle(itemName, room.name, btn);
                container.appendChild(btn);
            });
        }

        function selectItemForTitle(itemName, roomName, buttonElement) {
            document.querySelectorAll('.item-chip').forEach(btn => btn.classList.remove('active'));
            buttonElement.classList.add('active');

            // Format Judul: "[Nama Barang] [Nama Ruangan] - "
            const prefix = `${itemName} ${roomName} - `;
            const titleInput = document.getElementById('title');
            titleInput.value = prefix;
            titleInput.focus();
            titleInput.setSelectionRange(prefix.length, prefix.length);
        }

        // 4. Banner Anti-Duplikasi
        function checkActiveReportBanner(room) {
            const banner = document.getElementById('activeReportBanner');
            const bannerText = document.getElementById('activeReportText');
            counterDukungan = 1;
            document.getElementById('btnIkutLapor').innerText = 'Ikut Lapor (+1)';
            document.getElementById('btnIkutLapor').disabled = false;

            if (room.activeReport) {
                bannerText.innerText = `Laporan aktif di ruangan ini: ${room.activeReport}`;
                banner.classList.remove('hidden');
            } else {
                banner.classList.add('hidden');
            }
        }

        function tambahDukungan() {
            counterDukungan++;
            const btn = document.getElementById('btnIkutLapor');
            btn.innerText = `Ikut Lapor (+${counterDukungan})`;
            btn.classList.add('bg-amber-100', 'font-bold');
            alert('Dukungan Anda telah dicatat. Tim Sarpras memprioritaskan fasilitas dengan laporan yang didukung banyak warga sekolah.');
        }

        // 5. Logika Foto Kamera Langsung (Blokir Galeri)
        function triggerCameraCapture() {
            // Coba buka Live Camera getUserMedia jika didukung browser
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                openLiveCamera();
            } else {
                // Fallback ke input file dengan capture="environment"
                document.getElementById('photo_before').click();
            }
        }

        async function openLiveCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' } },
                    audio: false
                });
                activeMediaStream = stream;
                const videoEl = document.getElementById('cameraVideo');
                videoEl.srcObject = stream;
                videoEl.play();

                document.getElementById('cameraPromptCard').classList.add('hidden');
                document.getElementById('liveStreamContainer').classList.remove('hidden');
                document.getElementById('photoPreviewContainer').classList.add('hidden');
                lucide.createIcons();
            } catch (err) {
                // Jika izin video ditolak atau browser memblokir getUserMedia, gunakan native file capture camera
                document.getElementById('photo_before').click();
            }
        }

        function closeLiveCamera() {
            if (activeMediaStream) {
                activeMediaStream.getTracks().forEach(track => track.stop());
                activeMediaStream = null;
            }
            document.getElementById('liveStreamContainer').classList.add('hidden');
            document.getElementById('cameraPromptCard').classList.remove('hidden');
        }

        function captureLiveSnapshot() {
            const videoEl = document.getElementById('cameraVideo');
            const canvas = document.getElementById('snapshotCanvas');
            canvas.width = videoEl.videoWidth || 640;
            canvas.height = videoEl.videoHeight || 480;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(videoEl, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(blob => {
                if (!blob) return;
                const file = new File([blob], `foto_lokasi_${Date.now()}.jpg`, { type: 'image/jpeg' });
                
                // Masukkan file jepretan ke input[type="file"]
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('photo_before').files = dataTransfer.files;

                // Tampilkan preview
                showPreviewImage(URL.createObjectURL(blob), file.name, (file.size / 1024).toFixed(0) + ' KB');
                closeLiveCamera();
            }, 'image/jpeg', 0.85);
        }

        function handleNativeCapture(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    showPreviewImage(e.target.result, file.name, (file.size / 1024).toFixed(0) + ' KB');
                }
                reader.readAsDataURL(file);
            }
        }

        function showPreviewImage(srcUrl, fileName, fileSizeText) {
            document.getElementById('previewImageElement').src = srcUrl;
            document.getElementById('photoMetaInfo').innerText = `${fileName} (${fileSizeText})`;
            document.getElementById('cameraPromptCard').classList.add('hidden');
            document.getElementById('liveStreamContainer').classList.add('hidden');
            document.getElementById('photoPreviewContainer').classList.remove('hidden');
            lucide.createIcons();
        }

        function retakePhoto() {
            // Reset input file
            document.getElementById('photo_before').value = '';
            document.getElementById('previewImageElement').src = '#';
            document.getElementById('photoPreviewContainer').classList.add('hidden');
            document.getElementById('cameraPromptCard').classList.remove('hidden');

            // Buka kamera kembali
            triggerCameraCapture();
        }

        // Form Submit Loading Feedback
        document.getElementById('laporForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span>Mengirim Laporan ke Database...</span>';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>
</body>
</html>
