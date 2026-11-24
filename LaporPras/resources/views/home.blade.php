<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaporPras - Sistem Pelaporan Fasilitas SMK Negeri 4 Bandung</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#0F5132',
                            dark: '#0A3B24',
                            light: '#198754',
                            50: '#F0FDF4',
                            100: '#DCFCE7',
                        },
                        surface: '#F8FAFC',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FFFFFF;
            color: #0F172A;
        }

        /* Subtle hero background dot grid */
        .hero-pattern {
            background-image: radial-gradient(rgba(15, 81, 50, 0.08) 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }

        /* Soft gradient radial glow behind hero title */
        .hero-glow {
            background: radial-gradient(circle at 50% 25%, rgba(15, 81, 50, 0.06) 0%, rgba(255, 255, 255, 0) 70%);
        }

        /* Staggered entrance animations */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up-init {
            opacity: 0;
            animation: fadeUp 0.75s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }

        /* Interactive Card Hover */
        .feature-card {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                        border-color 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px -8px rgba(15, 81, 50, 0.08);
            border-color: #0F5132;
        }

        .arrow-slide {
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .feature-card:hover .arrow-slide {
            transform: translateX(4px);
        }

        /* Custom Lucide icon stroke width */
        [data-lucide] {
            stroke-width: 1.75px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between antialiased selection:bg-emerald-100 selection:text-emerald-900">

    <!-- Sticky Glassmorphic Navbar -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/80 transition-all duration-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center shadow-sm group-hover:bg-primary-dark transition">
                    <i data-lucide="wrench" class="w-4 h-4"></i>
                </div>
                <div>
                    <span class="text-base font-extrabold tracking-tight text-slate-900 block leading-tight">LaporPras</span>
                    <span class="text-[11px] font-medium text-slate-500 block leading-tight">SMK Negeri 4 Bandung</span>
                </div>
            </a>

            <!-- Nav Links & CTA -->
            <nav class="flex items-center gap-2 sm:gap-4">
                <a href="#alur-pelaporan" class="hidden sm:inline-flex items-center text-xs sm:text-sm font-medium text-slate-600 hover:text-primary transition px-2 py-1.5 rounded-lg hover:bg-slate-50">
                    Cara Kerja
                </a>
                <a href="{{ route('public.track.search') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-medium text-slate-600 hover:text-primary transition px-2.5 py-1.5 rounded-lg hover:bg-slate-50">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Lacak Tiket</span>
                </a>
                <button type="button" onclick="openLoginModal()" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2 rounded-xl transition shadow-sm cursor-pointer active:scale-[0.98]">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span>Login Sarpras</span>
                </button>
            </nav>
        </div>
    </header>

    <!-- Main Content Area with Dot Pattern -->
    <main class="flex-grow hero-pattern hero-glow w-full">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
            
            <!-- Hero Section -->
            <section class="text-center max-w-3xl mx-auto mb-14 sm:mb-16">
                <!-- Pill Badge -->
                <div class="fade-up-init delay-100 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-primary text-xs font-semibold mb-5 border border-emerald-200/80 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <span>Sistem Pelaporan Fasilitas Sekolah</span>
                </div>

                <!-- Main Title -->
                <h1 class="fade-up-init delay-200 text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-[1.18] sm:leading-[1.16] mb-5">
                    Fasilitas Rusak? <br class="hidden sm:inline">
                    <span class="text-primary">Laporkan Cepat, Kami Perbaiki.</span>
                </h1>

                <!-- Subtitle -->
                <p class="fade-up-init delay-300 text-slate-600 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto mb-8 font-normal">
                    Pindai kode QR di pintu ruangan atau pilih lokasi secara manual untuk melaporkan kerusakan sarana prasarana langsung dari ponsel Anda—tanpa perlu membuat akun.
                </p>

                <!-- Dual CTA Buttons -->
                <div class="fade-up-init delay-400 flex flex-col sm:flex-row items-center justify-center gap-3 max-w-md mx-auto">
                    <a href="{{ route('public.form', 'smkn-4-bandung') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white font-semibold text-sm px-6 py-3.5 rounded-xl shadow-sm hover:shadow transition active:scale-[0.98]">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span>Buat Laporan Sekarang</span>
                    </a>
                    <a href="{{ route('public.track.search') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm px-6 py-3.5 rounded-xl border border-slate-300 transition active:scale-[0.98]">
                        <i data-lucide="search" class="w-4 h-4 text-slate-500"></i>
                        <span>Cek Status Tiket</span>
                    </a>
                </div>
            </section>

            <!-- 3 Main Access Cards (Uniform & Monochromatic) -->
            <section class="fade-up-init delay-500 grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                
                <!-- Card 1 (Pelapor) -->
                <div class="feature-card bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-xs flex flex-col justify-between">
                    <div>
                        <!-- Header Icon & Badge -->
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-primary flex items-center justify-center">
                                <i data-lucide="camera" class="w-5 h-5"></i>
                            </div>
                            <span class="text-[11px] font-semibold tracking-wide text-primary bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                                Pelapor
                            </span>
                        </div>

                        <!-- Card Title & Description -->
                        <h2 class="text-lg font-bold text-slate-900 mb-2">Form Lapor Kerusakan</h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-5">
                            Pilih ruangan atau pindai QR pintu, ambil foto bukti langsung melalui kamera, dan kirim laporan dalam hitungan detik.
                        </p>

                        <!-- Feature Tags -->
                        <div class="flex flex-wrap gap-1.5 mb-6">
                            <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-600 border border-slate-200/80 px-2.5 py-1 rounded-md text-[11px] font-medium">
                                <i data-lucide="check" class="w-3 h-3 text-primary"></i>
                                Tanpa Login
                            </span>
                            <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-600 border border-slate-200/80 px-2.5 py-1 rounded-md text-[11px] font-medium">
                                <i data-lucide="camera" class="w-3 h-3 text-primary"></i>
                                Kamera Langsung
                            </span>
                        </div>
                    </div>

                    <!-- Button Action -->
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('public.form', 'smkn-4-bandung') }}" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-xs sm:text-sm font-semibold text-white bg-primary hover:bg-primary-dark transition shadow-xs active:scale-[0.98]">
                            <span>Buka Form Lapor</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 arrow-slide"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 2 (Transparansi) -->
                <div class="feature-card bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-xs flex flex-col justify-between">
                    <div>
                        <!-- Header Icon & Badge -->
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-primary flex items-center justify-center">
                                <i data-lucide="activity" class="w-5 h-5"></i>
                            </div>
                            <span class="text-[11px] font-semibold tracking-wide text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                                Transparansi
                            </span>
                        </div>

                        <!-- Card Title & Description -->
                        <h2 class="text-lg font-bold text-slate-900 mb-2">Lacak Tiket Laporan</h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-5">
                            Pantau perkembangan perbaikan secara real-time menggunakan kode tiket atau riwayat otomatis di perangkat Anda.
                        </p>

                        <!-- Feature Tags -->
                        <div class="flex flex-wrap gap-1.5 mb-6">
                            <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-600 border border-slate-200/80 px-2.5 py-1 rounded-md text-[11px] font-medium">
                                <i data-lucide="check" class="w-3 h-3 text-primary"></i>
                                Real-time
                            </span>
                            <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-600 border border-slate-200/80 px-2.5 py-1 rounded-md text-[11px] font-medium">
                                <i data-lucide="check" class="w-3 h-3 text-primary"></i>
                                Bukti Perbaikan
                            </span>
                        </div>
                    </div>

                    <!-- Button Action -->
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('public.track.search') }}" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 bg-surface hover:bg-slate-100 border border-slate-200 transition active:scale-[0.98]">
                            <span>Lacak Tiket Anda</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 arrow-slide"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 3 (Petugas) -->
                <div class="feature-card bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-xs flex flex-col justify-between">
                    <div>
                        <!-- Header Icon & Badge -->
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-primary flex items-center justify-center">
                                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                            </div>
                            <span class="text-[11px] font-semibold tracking-wide text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                                Petugas
                            </span>
                        </div>

                        <!-- Card Title & Description -->
                        <h2 class="text-lg font-bold text-slate-900 mb-2">Dashboard Sarpras</h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-5">
                            Kelola tiket kerusakan masuk, cetak kode QR tiap ruangan, dan ekspor rekapitulasi laporan bulanan.
                        </p>

                        <!-- Feature Tags -->
                        <div class="flex flex-wrap gap-1.5 mb-6">
                            <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-600 border border-slate-200/80 px-2.5 py-1 rounded-md text-[11px] font-medium">
                                <i data-lucide="check" class="w-3 h-3 text-primary"></i>
                                Manajemen Aset
                            </span>
                            <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-600 border border-slate-200/80 px-2.5 py-1 rounded-md text-[11px] font-medium">
                                <i data-lucide="check" class="w-3 h-3 text-primary"></i>
                                Export PDF & Excel
                            </span>
                        </div>
                    </div>

                    <!-- Button Action: Trigger Login Modal -->
                    <div class="pt-4 border-t border-slate-100">
                        <button type="button" onclick="openLoginModal()" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 bg-surface hover:bg-slate-100 border border-slate-200 transition active:scale-[0.98] cursor-pointer">
                            <span>Masuk ke Dashboard</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 arrow-slide"></i>
                        </button>
                    </div>
                </div>

            </section>

            <!-- 3-Step Horizontal Flow: Alur Pelaporan -->
            <section id="alur-pelaporan" class="pt-8 sm:pt-12 border-t border-slate-200/80 mb-6">
                <div class="text-center max-w-xl mx-auto mb-8 sm:mb-10">
                    <span class="text-xs font-bold uppercase tracking-wider text-primary">Alur Pelaporan</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">3 Langkah Mudah Tanpa Birokrasi</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                    
                    <!-- Step 01 -->
                    <div class="bg-white rounded-xl p-5 border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-mono text-sm font-bold text-primary">01</span>
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-primary flex items-center justify-center">
                                    <i data-lucide="qr-code" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1.5">Pindai QR Pintu / Pilih Ruang</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Pindai stiker QR yang terpasang di pintu ruangan atau pilih lokasi secara manual dari daftar kelas dan lab.
                            </p>
                        </div>
                    </div>

                    <!-- Step 02 -->
                    <div class="bg-white rounded-xl p-5 border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-mono text-sm font-bold text-primary">02</span>
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-primary flex items-center justify-center">
                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1.5">Pilih Barang & Foto Langsung</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Klik chip barang yang rusak, lalu ambil foto bukti kerusakan secara langsung di lokasi menggunakan kamera HP.
                            </p>
                        </div>
                    </div>

                    <!-- Step 03 -->
                    <div class="bg-white rounded-xl p-5 border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-mono text-sm font-bold text-primary">03</span>
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-primary flex items-center justify-center">
                                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1.5">Pantau Hingga Selesai</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Dapatkan nomor tiket unik secara instan untuk melacak status verifikasi, penanganan, hingga perbaikan rampung.
                            </p>
                        </div>
                    </div>

                </div>
            </section>

        </div>
    </main>

    <!-- Minimalist Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-xs text-slate-500">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} <strong>LaporPras</strong> &bull; SMK Negeri 4 Bandung. Sistem Pelaporan Fasilitas Sekolah.</p>
            <div class="flex items-center space-x-5">
                <a href="{{ route('public.form', 'smkn-4-bandung') }}" class="text-slate-600 hover:text-primary transition font-medium">Form Lapor</a>
                <a href="{{ route('public.track.search') }}" class="text-slate-600 hover:text-primary transition font-medium">Lacak Tiket</a>
                <button type="button" onclick="openLoginModal()" class="text-slate-600 hover:text-primary transition font-medium cursor-pointer">Dashboard Sarpras</button>
            </div>
        </div>
    </footer>

    <!-- Login Modal (Popup Dialog) -->
    <div id="loginModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="modalTitle" onclick="handleBackdropClick(event)">
        <!-- Backdrop with soft blur -->
        <div id="loginModalBackdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity duration-200 opacity-0"></div>

        <!-- Modal Card Container -->
        <div id="loginModalContent" class="relative w-full max-w-md bg-white rounded-2xl border border-slate-200/90 shadow-2xl p-6 sm:p-7 z-10 transform scale-95 opacity-0 transition-all duration-200 ease-out">
            
            <!-- Close Button (X) -->
            <button type="button" onclick="closeLoginModal()" class="absolute top-5 right-5 p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer" aria-label="Tutup dialog">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

            <!-- Modal Header -->
            <div class="flex items-start gap-3.5 mb-5">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-primary flex items-center justify-center shrink-0 border border-emerald-100/80">
                    <i data-lucide="key-round" class="w-5 h-5"></i>
                </div>
                <div class="pr-6">
                    <h3 id="modalTitle" class="text-lg font-bold text-slate-900 tracking-tight leading-snug">
                        Login
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                        LaporPras
                    </p>
                </div>
            </div>

            <!-- Soft Red Alert Message for Validation -->
            <div id="loginAlert" class="hidden mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200/90 text-rose-700 text-xs flex items-start gap-2.5 transition">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5"></i>
                <span id="loginAlertText">Email atau username dan kata sandi wajib diisi.</span>
            </div>

            <!-- Form -->
            <form id="loginForm" onsubmit="handleLoginSubmit(event)" novalidate class="space-y-4">
                @csrf

                <!-- Email or Username Input -->
                <div>
                    <label for="loginIdentifier" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Email atau Username
                    </label>
                    <input type="text" id="loginIdentifier" name="identifier" autocomplete="username"
                           placeholder="Masukkan email atau username staf"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                </div>

                <!-- Password Input with Toggle Eye Button -->
                <div>
                    <label for="loginPassword" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input type="password" id="loginPassword" name="password" autocomplete="current-password"
                               placeholder="Masukkan kata sandi"
                               class="w-full px-3.5 py-2.5 pr-11 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition cursor-pointer" aria-label="Tampilkan atau sembunyikan kata sandi">
                            <i id="passwordEyeIcon" data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Options Row: Remember Me & Reset Password Info -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" id="rememberMe" name="remember" class="w-4 h-4 rounded text-primary border-slate-300 focus:ring-primary/20 accent-[#0F5132] cursor-pointer">
                        <span class="text-xs text-slate-600 font-medium">Ingat sesi saya</span>
                    </label>
                            </div>

                <!-- Action Button: Full-width Emerald -->
                <div class="pt-2">
                    <button type="submit" id="loginSubmitBtn" class="w-full py-3 px-4 rounded-xl font-semibold text-xs sm:text-sm text-white bg-primary hover:bg-primary-dark transition shadow-sm active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                        <span id="loginBtnText">Masuk ke Dashboard</span>
                        <svg id="loginSpinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <i id="loginArrowIcon" data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Demo Helper Note -->
            <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Kredensial Demo Petugas:</span>
                <button type="button" onclick="fillDemoCredentials()" class="text-primary hover:underline font-semibold cursor-pointer">
                    Isi Otomatis (Demo)
                </button>
            </div>

        </div>
    </div>

    <!-- Scripts: Initialize Lucide & Modal Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });

        const loginModal = document.getElementById('loginModal');
        const loginModalBackdrop = document.getElementById('loginModalBackdrop');
        const loginModalContent = document.getElementById('loginModalContent');
        const loginAlert = document.getElementById('loginAlert');
        const loginAlertText = document.getElementById('loginAlertText');
        const loginIdentifier = document.getElementById('loginIdentifier');
        const loginPassword = document.getElementById('loginPassword');
        const loginSubmitBtn = document.getElementById('loginSubmitBtn');
        const loginBtnText = document.getElementById('loginBtnText');
        const loginSpinner = document.getElementById('loginSpinner');
        const loginArrowIcon = document.getElementById('loginArrowIcon');
        const passwordEyeIcon = document.getElementById('passwordEyeIcon');

        function openLoginModal() {
            // Hide alert and reset validation state
            hideAlert();

            loginModal.classList.remove('hidden');
            loginModal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            // Smooth entrance transition
            requestAnimationFrame(() => {
                loginModalBackdrop.classList.remove('opacity-0');
                loginModalBackdrop.classList.add('opacity-100');
                loginModalContent.classList.remove('opacity-0', 'scale-95');
                loginModalContent.classList.add('opacity-100', 'scale-100');
            });

            // Focus first input
            setTimeout(() => {
                loginIdentifier.focus();
            }, 100);

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function closeLoginModal() {
            loginModalBackdrop.classList.remove('opacity-100');
            loginModalBackdrop.classList.add('opacity-0');
            loginModalContent.classList.remove('opacity-100', 'scale-100');
            loginModalContent.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                loginModal.classList.remove('flex');
                loginModal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 200);
        }

        function handleBackdropClick(e) {
            if (e.target.id === 'loginModalBackdrop' || e.target.id === 'loginModal') {
                closeLoginModal();
            }
        }

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !loginModal.classList.contains('hidden')) {
                closeLoginModal();
            }
        });

        // Toggle password show / hide
        function togglePasswordVisibility() {
            if (loginPassword.type === 'password') {
                loginPassword.type = 'text';
                passwordEyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                loginPassword.type = 'password';
                passwordEyeIcon.setAttribute('data-lucide', 'eye');
            }
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function showAlert(message) {
            loginAlertText.textContent = message;
            loginAlert.classList.remove('hidden');
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function hideAlert() {
            loginAlert.classList.add('hidden');
        }

        function fillDemoCredentials() {
            loginIdentifier.value = 'sarpras@smkn4bdg.sch.id';
            loginPassword.value = 'admin123';
            hideAlert();
            loginPassword.focus();
        }

        function handleLoginSubmit(e) {
            e.preventDefault();
            const identifierVal = loginIdentifier.value.trim();
            const passwordVal = loginPassword.value.trim();

            if (!identifierVal || !passwordVal) {
                showAlert('Email atau username dan kata sandi wajib diisi.');
                if (!identifierVal) {
                    loginIdentifier.focus();
                } else {
                    loginPassword.focus();
                }
                return;
            }

            hideAlert();

            // Set loading state
            loginSubmitBtn.disabled = true;
            loginBtnText.textContent = 'Memverifikasi...';
            loginSpinner.classList.remove('hidden');
            loginArrowIcon.classList.add('hidden');

            // Simulate realistic brief verification before redirecting to dashboard
            setTimeout(() => {
                window.location.href = "{{ route('sarpras.index') }}";
            }, 600);
        }
    </script>
</body>
</html>
