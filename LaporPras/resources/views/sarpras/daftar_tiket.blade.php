<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarpras Admin - Manajemen Laporan Kerusakan & Aset</title>
    
    <!-- Fonts & Lucide Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --brand-green: #0F5132;
            --brand-green-hover: #0b3d26;
            --brand-green-subtle: #f0fdf4;
            --surface-soft: #f8fafc;
            --border-soft: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #ffffff;
            color: #1e293b;
        }

        /* Borderless vertical table */
        table.borderless-vertical th,
        table.borderless-vertical td {
            border-left: none !important;
            border-right: none !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        /* Active status card styling */
        .status-card.active {
            border-color: #0F5132 !important;
            background-color: #f0fdf4 !important;
            box-shadow: 0 1px 2px 0 rgba(15, 81, 50, 0.08);
        }
        .status-card.active .status-number {
            color: #0F5132 !important;
        }

        /* Collapsible Sidebar transition & layout rules */
        #sidebar {
            transition: width 250ms cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
        }

        #sidebar.collapsed {
            width: 72px !important;
        }
        #sidebar:not(.collapsed) {
            width: 250px !important;
        }

        #sidebar.collapsed .sidebar-expanded-only {
            display: none !important;
        }
        #sidebar:not(.collapsed) .sidebar-collapsed-only {
            display: none !important;
        }

        #sidebar.collapsed .sidebar-item {
            justify-content: center !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            .print-only {
                display: block !important;
            }
            aside {
                display: none !important;
            }
            main {
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-white min-h-screen flex antialiased">

    <!-- 1. COLLAPSIBLE SIDEBAR KIRI (Stripe Style, Rail Ikon / Expanded) -->
    <aside id="sidebar" class="no-print bg-white border-r border-[#E2E8F0] flex flex-col justify-between shrink-0 h-screen sticky top-0 z-30 select-none">
        
        <!-- Bagian Atas: Brand & Tombol Toggle -->
        <div class="p-4 border-b border-[#E2E8F0]/80">
            <div class="flex items-center justify-between gap-2">
                
                <!-- Brand Info (Mode Expanded) -->
                <div class="sidebar-expanded-only flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-xl bg-[#0F5132] text-white flex items-center justify-center shrink-0 shadow-xs">
                        <i data-lucide="building-2" class="w-5 h-5 stroke-[1.75]"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h2 class="text-sm font-bold text-slate-900 leading-tight truncate">Sarpras SMKN 4</h2>
                        <p class="text-[11px] text-slate-400 font-medium truncate">Bandung &bull; Panel Admin</p>
                    </div>
                </div>

                <!-- Brand Logo Icon Only (Mode Collapsed) -->
                <div class="sidebar-collapsed-only mx-auto">
                    <div class="w-9 h-9 rounded-xl bg-[#0F5132] text-white flex items-center justify-center shadow-xs" title="Sarpras SMKN 4 Bandung">
                        <i data-lucide="building-2" class="w-5 h-5 stroke-[1.75]"></i>
                    </div>
                </div>

                <!-- Tombol Toggle Buka/Tutup Sidebar -->
                <button type="button" onclick="toggleSidebar()" id="sidebarToggleBtn" 
                        class="p-1.5 rounded-xl text-slate-400 hover:text-slate-800 hover:bg-[#F8FAFC] transition cursor-pointer shrink-0" 
                        title="Perkecil / Perbesar Sidebar">
                    <i id="sidebarToggleIcon" data-lucide="panel-left-close" class="w-4 h-4 stroke-[1.75]"></i>
                </button>
            </div>
        </div>

        <!-- Bagian Tengah: Menu Utama Navigasi -->
        <div class="p-3 flex-grow overflow-y-auto">
            <nav class="space-y-1" id="sidebarNav">
                
                <!-- 1. Ringkasan (Overview) -->
                <button type="button" onclick="showOverviewTab()" id="navItemRingkasan" 
                        class="sidebar-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-[#F8FAFC] transition group cursor-pointer"
                        title="Ringkasan">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 stroke-[1.75] text-slate-400 group-hover:text-slate-700 shrink-0"></i>
                    <span class="sidebar-expanded-only truncate">Ringkasan</span>
                </button>

                <!-- 2. Laporan Masuk (Default Aktif) -->
                <button type="button" onclick="switchMainView('reports')" id="navItemReports" 
                        class="sidebar-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold bg-[#0F5132]/10 text-[#0F5132] transition group cursor-pointer"
                        title="Laporan Masuk ({{ $totalAll }})">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <i data-lucide="inbox" class="w-4 h-4 stroke-[1.75] text-[#0F5132] shrink-0"></i>
                        <span class="sidebar-expanded-only truncate">Laporan Masuk</span>
                    </div>
                    <span class="sidebar-expanded-only text-[10px] font-bold font-mono px-2 py-0.5 rounded-full bg-[#0F5132] text-white shrink-0">
                        {{ $totalAll }}
                    </span>
                </button>

                <!-- 3. Data Ruangan & QR -->
                <button type="button" onclick="openQrModal(1)" id="navItemQr" 
                        class="sidebar-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-[#F8FAFC] transition group cursor-pointer"
                        title="Data Ruangan & QR">
                    <i data-lucide="qr-code" class="w-4 h-4 stroke-[1.75] text-slate-400 group-hover:text-slate-700 shrink-0"></i>
                    <span class="sidebar-expanded-only truncate">Data Ruangan & QR</span>
                </button>

                <!-- 4. Master Inventaris Barang (Halaman Penuh) -->
                <button type="button" onclick="switchMainView('inventory')" id="navItemInventory" 
                        class="sidebar-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-[#F8FAFC] transition group cursor-pointer"
                        title="Master Inventaris Barang">
                    <i data-lucide="package" class="w-4 h-4 stroke-[1.75] text-slate-400 group-hover:text-slate-700 shrink-0"></i>
                    <span class="sidebar-expanded-only truncate">Master Inventaris Barang</span>
                </button>

                <!-- 5. Utility (Khusus Master Admin) -->
                <button type="button" onclick="switchMainView('utility')" id="navItemUtility" 
                        class="sidebar-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-[#F8FAFC] transition group cursor-pointer"
                        title="Utility (Master Admin)">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <i data-lucide="shield-alert" class="w-4 h-4 stroke-[1.75] text-slate-400 group-hover:text-slate-700 shrink-0"></i>
                        <span class="sidebar-expanded-only truncate">Utility</span>
                    </div>
                    <span class="sidebar-expanded-only text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 shrink-0">
                        Admin
                    </span>
                </button>

            </nav>
        </div>

        <!-- Bagian Bawah: Profil Pengguna & Keluar -->
        <div class="p-3 border-t border-[#E2E8F0] space-y-2.5 bg-[#F8FAFC]/50">
            
            <!-- Profil User (Expanded) -->
            <div class="sidebar-expanded-only flex items-center justify-between px-1">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-[#0F5132] text-white flex items-center justify-center text-xs font-bold font-mono shrink-0">
                        KN
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-slate-800 truncate">Kennan</p>
                        <p id="sidebarRoleLabel" class="text-[10px] font-semibold text-emerald-700 truncate">Master Admin</p>
                    </div>
                </div>

                <!-- Tombol Switch Peran Demo -->
                <button type="button" onclick="toggleUserRoleDemo()" 
                        class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 transition cursor-pointer" 
                        title="Ganti Peran: Master Admin / Teknisi Sarpras">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5 stroke-[1.75]"></i>
                </button>
            </div>

            <!-- Profil User (Collapsed) -->
            <div class="sidebar-collapsed-only flex flex-col items-center gap-2 py-1">
                <div class="w-8 h-8 rounded-full bg-[#0F5132] text-white flex items-center justify-center text-xs font-bold font-mono" title="Kennan (Master Admin)">
                    KN
                </div>
            </div>

            <!-- Action Links -->
            <div class="pt-2 border-t border-[#E2E8F0] space-y-1">
                <a href="{{ route('public.form', 'smkn-4-bandung') }}" target="_blank" 
                   class="sidebar-item flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-xs text-slate-500 hover:text-slate-900 hover:bg-white transition" 
                   title="Buka Portal Pelapor Siswa">
                    <i data-lucide="external-link" class="w-3.5 h-3.5 stroke-[1.75] shrink-0"></i>
                    <span class="sidebar-expanded-only truncate">Portal Pelapor</span>
                </a>
                <a href="{{ route('home') }}" 
                   class="sidebar-item flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-xs text-slate-500 hover:text-rose-700 hover:bg-white transition" 
                   title="Keluar ke Beranda">
                    <i data-lucide="log-out" class="w-3.5 h-3.5 stroke-[1.75] shrink-0"></i>
                    <span class="sidebar-expanded-only truncate">Keluar</span>
                </a>
            </div>

        </div>

    </aside>

    <!-- 2. KONTEN UTAMA (Stripe Style Dynamic Views) -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        
        <!-- ======================================================== -->
        <!-- VIEW A: LAPORAN MASUK (DEFAULT VIEW)                     -->
        <!-- ======================================================== -->
        <div id="viewReports" class="flex flex-col flex-1">
            
            <!-- Header Halaman Laporan -->
            <header class="bg-white border-b border-[#E2E8F0] px-6 py-5 sticky top-0 z-20">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            Laporan Kerusakan
                        </h1>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Kelola dan tindak lanjuti laporan kerusakan sarana prasarana sekolah secara terpusat
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5 shrink-0">
                        <!-- Tombol Sekunder: + Tambah Ruangan / QR -->
                        <button type="button" onclick="openQrModal()" class="px-3.5 py-2 rounded-xl border border-[#E2E8F0] bg-white hover:bg-[#F8FAFC] text-slate-700 text-xs font-semibold flex items-center gap-2 transition active:scale-[0.98] shadow-2xs cursor-pointer">
                            <i data-lucide="qr-code" class="w-4 h-4 stroke-[1.75] text-slate-500"></i>
                            <span>+ Tambah Ruangan / QR</span>
                        </button>

                        <!-- Tombol Primer: Export Laporan (Dropdown PDF / Excel) -->
                        <div class="relative">
                            <button type="button" id="btnExport" onclick="toggleExportDropdown()" class="px-3.5 py-2 rounded-xl bg-[#0F5132] hover:bg-[#0b3d26] text-white text-xs font-bold flex items-center gap-2 transition active:scale-[0.98] shadow-xs cursor-pointer">
                                <i data-lucide="download" class="w-4 h-4 stroke-[1.75]"></i>
                                <span>Export Laporan</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 stroke-[1.75] text-emerald-200"></i>
                            </button>

                            <div id="exportDropdownMenu" class="hidden absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-[#E2E8F0] py-2 z-50 text-slate-800">
                                <div class="px-3 py-1.5 border-b border-[#E2E8F0] text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    Pilihan Unduhan
                                </div>
                                <button type="button" onclick="exportToPDF()" class="w-full px-3.5 py-2.5 text-left text-xs font-semibold hover:bg-[#F8FAFC] flex items-center gap-2.5 transition">
                                    <i data-lucide="file-text" class="w-4 h-4 stroke-[1.75] text-rose-600"></i>
                                    <div>
                                        <div class="font-bold text-slate-900">Export ke PDF</div>
                                        <div class="text-[10px] text-slate-400">Format cetak berkop surat resmi</div>
                                    </div>
                                </button>
                                <button type="button" onclick="exportToExcel()" class="w-full px-3.5 py-2.5 text-left text-xs font-semibold hover:bg-[#F8FAFC] flex items-center gap-2.5 transition">
                                    <i data-lucide="file-spreadsheet" class="w-4 h-4 stroke-[1.75] text-emerald-700"></i>
                                    <div>
                                        <div class="font-bold text-slate-900">Export ke Excel (.csv)</div>
                                        <div class="text-[10px] text-slate-400">Format data spreadsheet terinci</div>
                                    </div>
                                </button>
                                <div class="my-1 border-t border-[#E2E8F0]"></div>
                                <button type="button" onclick="window.print()" class="w-full px-3.5 py-2 text-left text-xs font-semibold hover:bg-[#F8FAFC] flex items-center gap-2.5 transition text-slate-600">
                                    <i data-lucide="printer" class="w-4 h-4 stroke-[1.75]"></i>
                                    <span>Cetak Langsung (Print Preview)</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Isi Laporan: Cards, Filter, dan Tabel Tiket -->
            <main class="max-w-7xl mx-auto px-6 py-6 w-full space-y-6 flex-grow">
                
                <!-- Banner Informasi / Insight Singkat -->
                @if($daruratPendingCount > 0)
                <div id="insightBanner" class="p-3.5 rounded-xl border border-rose-200 bg-rose-50/70 text-slate-800 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="alert-circle" class="w-4 h-4 stroke-[1.75] text-rose-600 shrink-0"></i>
                        <span>Terdapat <strong>{{ $daruratPendingCount }} laporan</strong> dengan tingkat urgensi Darurat yang belum ditangani.</span>
                    </div>
                    <button type="button" onclick="filterByPriority('Darurat')" class="font-bold text-rose-700 hover:text-rose-900 underline text-xs shrink-0 ml-3 cursor-pointer">
                        Lihat Prioritas
                    </button>
                </div>
                @endif

                <!-- 5 Kartu Filter Status (Interactive Summary Cards) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
                    <button type="button" onclick="filterStatus('Semua', this)" class="status-card active p-4 rounded-2xl border border-[#E2E8F0] bg-white text-left transition select-none hover:border-slate-300 cursor-pointer">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Semua Laporan</span>
                        <div class="status-number text-2xl font-extrabold text-slate-900 font-mono mt-1">{{ $totalAll }}</div>
                    </button>

                    <button type="button" onclick="filterStatus('Menunggu', this)" class="status-card p-4 rounded-2xl border border-[#E2E8F0] bg-white text-left transition select-none hover:border-slate-300 cursor-pointer">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Menunggu (Pending)</span>
                        <div class="status-number text-2xl font-extrabold text-slate-900 font-mono mt-1">{{ $totalMenunggu }}</div>
                    </button>

                    <button type="button" onclick="filterStatus('Diproses', this)" class="status-card p-4 rounded-2xl border border-[#E2E8F0] bg-white text-left transition select-none hover:border-slate-300 cursor-pointer">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Diproses (In Progress)</span>
                        <div class="status-number text-2xl font-extrabold text-slate-900 font-mono mt-1">{{ $totalDiproses }}</div>
                    </button>

                    <button type="button" onclick="filterStatus('Selesai', this)" class="status-card p-4 rounded-2xl border border-[#E2E8F0] bg-white text-left transition select-none hover:border-slate-300 cursor-pointer">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Selesai (Resolved)</span>
                        <div class="status-number text-2xl font-extrabold text-slate-900 font-mono mt-1">{{ $totalSelesai }}</div>
                    </button>

                    <button type="button" onclick="filterStatus('Ditolak', this)" class="status-card p-4 rounded-2xl border border-[#E2E8F0] bg-white text-left transition select-none hover:border-slate-300 cursor-pointer">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Ditolak (Rejected)</span>
                        <div class="status-number text-2xl font-extrabold text-slate-900 font-mono mt-1">{{ $totalDitolak }}</div>
                    </button>
                </div>

                <!-- Baris Filter Cepat & Pencarian -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 pt-2">
                    <div class="relative flex-1 max-w-md">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4 stroke-[1.75]"></i>
                        </span>
                        <input type="text" id="searchInput" oninput="applyFilters()" 
                            placeholder="Cari nama barang, ruangan, atau kode tiket..." 
                            class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl pl-9 pr-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-[#0F5132] focus:ring-1 focus:ring-[#0F5132] transition">
                    </div>

                    <div class="flex items-center gap-2 flex-wrap text-xs">
                        <div class="relative">
                            <button type="button" onclick="toggleDateFilter()" id="btnFilterDate" class="px-3 py-1.5 rounded-xl border border-[#E2E8F0] bg-white hover:bg-[#F8FAFC] text-slate-700 font-medium flex items-center gap-1.5 transition">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 stroke-[1.75] text-slate-400"></i>
                                <span id="dateFilterLabel">+ Tanggal</span>
                            </button>
                            <div id="dateFilterDropdown" class="hidden absolute right-0 mt-1 w-44 rounded-xl bg-white shadow-lg border border-[#E2E8F0] py-1 z-30 text-xs">
                                <button type="button" onclick="setDateFilter('all', '+ Tanggal')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Semua Tanggal</button>
                                <button type="button" onclick="setDateFilter('today', 'Hari Ini')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Hari Ini (24 Nov)</button>
                                <button type="button" onclick="setDateFilter('week', '7 Hari Terakhir')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">7 Hari Terakhir</button>
                            </div>
                        </div>

                        <div class="relative">
                            <button type="button" onclick="toggleRoomFilter()" id="btnFilterRoom" class="px-3 py-1.5 rounded-xl border border-[#E2E8F0] bg-white hover:bg-[#F8FAFC] text-slate-700 font-medium flex items-center gap-1.5 transition">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 stroke-[1.75] text-slate-400"></i>
                                <span id="roomFilterLabel">+ Ruangan</span>
                            </button>
                            <div id="roomFilterDropdown" class="hidden absolute right-0 mt-1 w-52 rounded-xl bg-white shadow-lg border border-[#E2E8F0] py-1 z-30 text-xs max-h-56 overflow-y-auto">
                                <button type="button" onclick="setRoomFilter('all', '+ Ruangan')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Semua Ruangan</button>
                                <button type="button" onclick="setRoomFilter('Lab Komputer RPL 1', 'Lab RPL 1')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Lab Komputer RPL 1</button>
                                <button type="button" onclick="setRoomFilter('Lab Jaringan TKJ', 'Lab TKJ')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Lab Jaringan TKJ</button>
                                <button type="button" onclick="setRoomFilter('Ruang Guru', 'Ruang Guru')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Ruang Guru & Kurikulum</button>
                                <button type="button" onclick="setRoomFilter('Perpustakaan', 'Perpustakaan')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Perpustakaan</button>
                                <button type="button" onclick="setRoomFilter('Toilet', 'Toilet')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Toilet Siswa Lt. 2</button>
                            </div>
                        </div>

                        <div class="relative">
                            <button type="button" onclick="togglePriorityFilter()" id="btnFilterPriority" class="px-3 py-1.5 rounded-xl border border-[#E2E8F0] bg-white hover:bg-[#F8FAFC] text-slate-700 font-medium flex items-center gap-1.5 transition">
                                <i data-lucide="gauge" class="w-3.5 h-3.5 stroke-[1.75] text-slate-400"></i>
                                <span id="priorityFilterLabel">+ Tingkat Urgensi</span>
                            </button>
                            <div id="priorityFilterDropdown" class="hidden absolute right-0 mt-1 w-44 rounded-xl bg-white shadow-lg border border-[#E2E8F0] py-1 z-30 text-xs">
                                <button type="button" onclick="setPriorityFilter('all', '+ Tingkat Urgensi')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Semua Urgensi</button>
                                <button type="button" onclick="setPriorityFilter('Darurat', 'Darurat')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC] text-rose-700 font-bold">Darurat</button>
                                <button type="button" onclick="setPriorityFilter('Tinggi', 'Tinggi')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC] text-amber-700 font-bold">Tinggi</button>
                                <button type="button" onclick="setPriorityFilter('Sedang', 'Sedang')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Sedang</button>
                                <button type="button" onclick="setPriorityFilter('Rendah', 'Rendah')" class="w-full px-3 py-1.5 text-left hover:bg-[#F8FAFC]">Rendah</button>
                            </div>
                        </div>

                        <button type="button" onclick="toggleSortUpvote()" id="btnSortUpvote" class="px-3 py-1.5 rounded-xl border border-[#E2E8F0] bg-white hover:bg-[#F8FAFC] text-slate-700 font-medium flex items-center gap-1.5 transition">
                            <i data-lucide="arrow-up-down" class="w-3.5 h-3.5 stroke-[1.75] text-slate-400"></i>
                            <span id="sortLabel">+ Urutkan (Terbanyak +1 Upvote)</span>
                        </button>

                        <button type="button" onclick="resetAllFilters()" id="btnResetFilters" class="hidden px-2.5 py-1.5 rounded-xl text-rose-600 hover:bg-rose-50 text-xs font-semibold transition">
                            Reset Filter
                        </button>
                    </div>
                </div>

                <!-- Tabel Data Laporan (Borderless Vertical) -->
                <div class="rounded-2xl border border-[#E2E8F0] bg-white overflow-hidden shadow-2xs">
                    <div class="overflow-x-auto">
                        <table class="borderless-vertical w-full text-left border-collapse" id="ticketsTable">
                            <thead>
                                <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC] text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-3.5 px-4 font-semibold">Kode & Waktu</th>
                                    <th class="py-3.5 px-4 font-semibold">Ruangan & Barang</th>
                                    <th class="py-3.5 px-4 font-semibold">Keluhan & Pelapor</th>
                                    <th class="py-3.5 px-4 font-semibold text-center">Dukungan</th>
                                    <th class="py-3.5 px-4 font-semibold text-center">Urgensi</th>
                                    <th class="py-3.5 px-4 font-semibold text-center">Status</th>
                                    <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E2E8F0] text-xs text-slate-700" id="ticketsTbody">
                                @forelse($tickets as $ticket)
                                <tr class="ticket-row hover:bg-[#F8FAFC]/80 transition group"
                                    data-code="{{ strtolower($ticket['ticket_code'] ?? '') }}"
                                    data-room="{{ strtolower($ticket['room_name'] ?? '') }}"
                                    data-asset="{{ strtolower($ticket['asset_name'] ?? '') }}"
                                    data-title="{{ strtolower($ticket['title'] ?? '') }}"
                                    data-reporter="{{ strtolower($ticket['reporter_name'] ?? '') }}"
                                    data-priority="{{ $ticket['priority'] ?? 'Sedang' }}"
                                    data-status="{{ $ticket['status'] ?? 'Menunggu' }}"
                                    data-upvotes="{{ $ticket['upvotes'] ?? 1 }}">
                                    
                                    <td class="py-3.5 px-4 align-top">
                                        <div class="font-mono font-bold text-slate-900 group-hover:text-[#0F5132] transition">
                                            {{ $ticket['ticket_code'] ?? '#SRP-' . $ticket['id'] }}
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $ticket['created_at_fmt'] ?? '24 Nov, 09:15' }}
                                        </div>
                                    </td>

                                    <td class="py-3.5 px-4 align-top max-w-[220px]">
                                        <div class="font-bold text-slate-900 truncate" title="{{ $ticket['room_name'] ?? '-' }}">
                                            {{ $ticket['room_name'] ?? '-' }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 truncate" title="{{ $ticket['asset_name'] ?? 'Non-Aset' }}">
                                            {{ $ticket['asset_name'] ?? 'Non-Aset' }}
                                        </div>
                                    </td>

                                    <td class="py-3.5 px-4 align-top max-w-[260px]">
                                        <div class="font-medium text-slate-900 truncate" title="{{ $ticket['title'] ?? '-' }}">
                                            {{ $ticket['title'] ?? '-' }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5 truncate">
                                            <span class="font-medium text-slate-700">{{ $ticket['reporter_name'] ?? '-' }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $ticket['reporter_info'] ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <td class="py-3.5 px-4 align-top text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 font-mono text-[11px] font-bold px-2 py-0.5 rounded-full bg-[#0F5132]/10 text-[#0F5132] border border-[#0F5132]/20">
                                            <i data-lucide="thumbs-up" class="w-3 h-3 stroke-[1.75]"></i>
                                            <span>+{{ $ticket['upvotes'] ?? 1 }} Pelapor</span>
                                        </span>
                                    </td>

                                    <td class="py-3.5 px-4 align-top text-center whitespace-nowrap">
                                        @php
                                            $pri = $ticket['priority'] ?? 'Sedang';
                                            $priClass = match($pri) {
                                                'Darurat' => 'bg-rose-50 text-rose-800 border-rose-200 font-bold',
                                                'Tinggi'  => 'bg-amber-50 text-amber-800 border-amber-200 font-semibold',
                                                'Sedang'  => 'bg-slate-100 text-slate-700 border-slate-200 font-medium',
                                                'Rendah'  => 'bg-slate-50 text-slate-500 border-slate-200 font-normal',
                                                default   => 'bg-slate-100 text-slate-700 border-slate-200 font-medium'
                                            };
                                        @endphp
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] border {{ $priClass }}">
                                            {{ $pri }}
                                        </span>
                                    </td>

                                    <td class="py-3.5 px-4 align-top text-center whitespace-nowrap">
                                        @php
                                            $st = $ticket['status'] ?? 'Menunggu';
                                            $stClass = match($st) {
                                                'Menunggu', 'Baru', 'Pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'Diproses', 'In Progress', 'Dalam Pengerjaan' => 'bg-blue-50 text-blue-800 border-blue-200',
                                                'Selesai', 'Resolved' => 'bg-emerald-50 text-emerald-800 border-emerald-200 font-bold',
                                                'Ditolak', 'Rejected' => 'bg-rose-50 text-rose-800 border-rose-200',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200'
                                            };
                                        @endphp
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] border {{ $stClass }}">
                                            {{ $st }}
                                        </span>
                                    </td>

                                    <td class="py-3.5 px-4 align-top text-right whitespace-nowrap">
                                        <button type="button" onclick='openDetailDrawer(@json($ticket))' 
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-[#E2E8F0] hover:border-slate-400 bg-white hover:bg-[#F8FAFC] text-slate-800 text-xs font-semibold transition active:scale-[0.98] shadow-2xs cursor-pointer">
                                            <span>Detail & Proses</span>
                                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 stroke-[1.75] text-slate-400"></i>
                                        </button>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <div class="max-w-xs mx-auto space-y-2">
                                            <i data-lucide="inbox" class="w-8 h-8 stroke-[1.75] mx-auto text-slate-300"></i>
                                            <p class="font-medium text-xs">Belum ada laporan kerusakan yang tercatat.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Table Stats -->
                    <div class="p-3.5 border-t border-[#E2E8F0] bg-[#F8FAFC] flex items-center justify-between text-xs text-slate-500">
                        <div>
                            Menampilkan <span id="visibleCount" class="font-bold text-slate-800">{{ $totalAll }}</span> dari <span class="font-bold text-slate-800">{{ $totalAll }}</span> total laporan
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 text-[11px] text-slate-400">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Live Data Sarpras SMKN 4
                            </span>
                        </div>
                    </div>
                </div>

            </main>
        </div>

        <!-- ======================================================== -->
        <!-- VIEW B: MASTER INVENTARIS BARANG (HALAMAN PENUH)        -->
        <!-- ======================================================== -->
        <div id="viewInventory" class="hidden flex flex-col flex-1">
            
            <!-- Header Halaman Inventaris -->
            <header class="bg-white border-b border-[#E2E8F0] px-6 py-5 sticky top-0 z-20">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            Master Inventaris Barang
                        </h1>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Kelola daftar aset dan fasilitas terdaftar pada setiap ruangan
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5 shrink-0">
                        <button type="button" onclick="openAddAssetModal()" class="px-3.5 py-2 rounded-xl bg-[#0F5132] hover:bg-[#0b3d26] text-white text-xs font-bold flex items-center gap-2 transition active:scale-[0.98] shadow-xs cursor-pointer">
                            <i data-lucide="plus" class="w-4 h-4 stroke-[1.75]"></i>
                            <span>+ Tambah Barang / Aset</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Isi Halaman Inventaris -->
            <main class="max-w-7xl mx-auto px-6 py-6 w-full space-y-6 flex-grow">
                
                <!-- Quick KPI Summary Strip -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                    <div class="p-4 rounded-2xl border border-[#E2E8F0] bg-white">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Aset Terdata</span>
                        <div class="text-2xl font-extrabold text-slate-900 font-mono mt-1" id="invTotalCount">14 Item</div>
                    </div>
                    <div class="p-4 rounded-2xl border border-[#E2E8F0] bg-white">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Kondisi Baik</span>
                        <div class="text-2xl font-extrabold text-emerald-700 font-mono mt-1" id="invBaikCount">9 Item</div>
                    </div>
                    <div class="p-4 rounded-2xl border border-[#E2E8F0] bg-white">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Dalam Perbaikan</span>
                        <div class="text-2xl font-extrabold text-amber-700 font-mono mt-1" id="invPerbaikanCount">3 Item</div>
                    </div>
                    <div class="p-4 rounded-2xl border border-[#E2E8F0] bg-white">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Kondisi Rusak</span>
                        <div class="text-2xl font-extrabold text-rose-700 font-mono mt-1" id="invRusakCount">2 Item</div>
                    </div>
                </div>

                <!-- Baris Pencarian Cepat & Filter Dropdown -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                    <div class="relative flex-1 max-w-md">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4 stroke-[1.75]"></i>
                        </span>
                        <input type="text" id="inventorySearchInput" oninput="filterInventoryTable()" 
                            placeholder="Cari kode aset atau nama barang..." 
                            class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl pl-9 pr-3.5 py-2.5 focus:bg-white focus:outline-none focus:border-[#0F5132] focus:ring-1 focus:ring-[#0F5132] transition">
                    </div>

                    <div class="flex items-center gap-2 flex-wrap text-xs">
                        <!-- Dropdown Ruangan -->
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-medium">Ruangan:</span>
                            <select id="inventoryRoomSelect" onchange="filterInventoryTable()" 
                                    class="bg-white border border-[#E2E8F0] text-slate-800 text-xs rounded-xl px-3 py-2 font-medium focus:outline-none focus:border-[#0F5132]">
                                <option value="all">Semua Ruangan</option>
                                <option value="Lab Komputer RPL 1">Lab Komputer RPL 1</option>
                                <option value="Lab Jaringan TKJ">Lab Jaringan TKJ</option>
                                <option value="Ruang Guru & Kurikulum">Ruang Guru & Kurikulum</option>
                                <option value="Perpustakaan Utama">Perpustakaan Utama</option>
                                <option value="Toilet Siswa Lt. 2">Toilet Siswa Lt. 2</option>
                                <option value="Ruang Teori 12 RPL">Ruang Teori 12 RPL</option>
                                <option value="Aula Sekolah">Aula Sekolah</option>
                            </select>
                        </div>

                        <!-- Dropdown Kondisi -->
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-500 font-medium">Kondisi:</span>
                            <select id="inventoryConditionSelect" onchange="filterInventoryTable()" 
                                    class="bg-white border border-[#E2E8F0] text-slate-800 text-xs rounded-xl px-3 py-2 font-medium focus:outline-none focus:border-[#0F5132]">
                                <option value="all">Semua Kondisi</option>
                                <option value="Baik">Baik</option>
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data Aset Penuh (Borderless Vertical) -->
                <div class="rounded-2xl border border-[#E2E8F0] bg-white overflow-hidden shadow-2xs">
                    <div class="overflow-x-auto">
                        <table class="borderless-vertical w-full text-left border-collapse" id="inventoryTable">
                            <thead>
                                <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC] text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-3.5 px-4 font-semibold">Kode Aset</th>
                                    <th class="py-3.5 px-4 font-semibold">Nama Barang</th>
                                    <th class="py-3.5 px-4 font-semibold">Ruangan</th>
                                    <th class="py-3.5 px-4 font-semibold text-center">Jumlah Unit</th>
                                    <th class="py-3.5 px-4 font-semibold text-center">Kondisi</th>
                                    <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E2E8F0] text-xs text-slate-700" id="inventoryTbody">
                                <tr class="inv-row hover:bg-[#F8FAFC]/80 transition" data-code="ast-rpl-01" data-name="ac daikin 2 pk split" data-room="Lab Komputer RPL 1" data-condition="Perbaikan">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">AST-RPL-01</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">AC Daikin 2 PK Split Inverter</td>
                                    <td class="py-3.5 px-4 text-slate-600">Lab Komputer RPL 1</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-medium">2 Unit</td>
                                    <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-800 border border-amber-200">Perbaikan</span></td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="editAsset('AST-RPL-01', 'AC Daikin 2 PK Split Inverter', 'Lab Komputer RPL 1', 2, 'Perbaikan')" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition mr-1" title="Edit Aset"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        <button type="button" onclick="deleteAsset(this, 'AST-RPL-01')" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus Aset"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                    </td>
                                </tr>
                                <tr class="inv-row hover:bg-[#F8FAFC]/80 transition" data-code="ast-rpl-02" data-name="pc all-in-one hp proone 400" data-room="Lab Komputer RPL 1" data-condition="Baik">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">AST-RPL-02</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">PC All-in-One HP ProOne Core i5</td>
                                    <td class="py-3.5 px-4 text-slate-600">Lab Komputer RPL 1</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-medium">36 Unit</td>
                                    <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">Baik</span></td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="editAsset('AST-RPL-02', 'PC All-in-One HP ProOne Core i5', 'Lab Komputer RPL 1', 36, 'Baik')" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition mr-1" title="Edit Aset"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        <button type="button" onclick="deleteAsset(this, 'AST-RPL-02')" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus Aset"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                    </td>
                                </tr>
                                <tr class="inv-row hover:bg-[#F8FAFC]/80 transition" data-code="ast-rpl-03" data-name="proyektor epson eb-x500" data-room="Lab Komputer RPL 1" data-condition="Baik">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">AST-RPL-03</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">Proyektor Epson EB-X500 3600 ANSI</td>
                                    <td class="py-3.5 px-4 text-slate-600">Lab Komputer RPL 1</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-medium">1 Unit</td>
                                    <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">Baik</span></td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="editAsset('AST-RPL-03', 'Proyektor Epson EB-X500 3600 ANSI', 'Lab Komputer RPL 1', 1, 'Baik')" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition mr-1" title="Edit Aset"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        <button type="button" onclick="deleteAsset(this, 'AST-RPL-03')" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus Aset"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                    </td>
                                </tr>
                                <tr class="inv-row hover:bg-[#F8FAFC]/80 transition" data-code="ast-tkj-01" data-name="switch cisco catalyst 2960" data-room="Lab Jaringan TKJ" data-condition="Perbaikan">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">AST-TKJ-01</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">Switch Cisco Catalyst 2960 24-Port</td>
                                    <td class="py-3.5 px-4 text-slate-600">Lab Jaringan TKJ</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-medium">4 Unit</td>
                                    <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-800 border border-amber-200">Perbaikan</span></td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="editAsset('AST-TKJ-01', 'Switch Cisco Catalyst 2960 24-Port', 'Lab Jaringan TKJ', 4, 'Perbaikan')" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition mr-1" title="Edit Aset"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        <button type="button" onclick="deleteAsset(this, 'AST-TKJ-01')" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus Aset"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                    </td>
                                </tr>
                                <tr class="inv-row hover:bg-[#F8FAFC]/80 transition" data-code="ast-tkj-02" data-name="router mikrotik ccr1009" data-room="Lab Jaringan TKJ" data-condition="Baik">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">AST-TKJ-02</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">Router MikroTik Cloud Core CCR1009</td>
                                    <td class="py-3.5 px-4 text-slate-600">Lab Jaringan TKJ</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-medium">2 Unit</td>
                                    <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">Baik</span></td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="editAsset('AST-TKJ-02', 'Router MikroTik Cloud Core CCR1009', 'Lab Jaringan TKJ', 2, 'Baik')" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition mr-1" title="Edit Aset"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        <button type="button" onclick="deleteAsset(this, 'AST-TKJ-02')" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus Aset"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                    </td>
                                </tr>
                                <tr class="inv-row hover:bg-[#F8FAFC]/80 transition" data-code="ast-teo-01" data-name="proyektor epson eb-e01" data-room="Ruang Teori 12 RPL" data-condition="Rusak">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">AST-TEO-01</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">Proyektor Epson EB-E01 (Lampu Mati)</td>
                                    <td class="py-3.5 px-4 text-slate-600">Ruang Teori 12 RPL</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-medium">1 Unit</td>
                                    <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-rose-50 text-rose-800 border border-rose-200">Rusak</span></td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="editAsset('AST-TEO-01', 'Proyektor Epson EB-E01 (Lampu Mati)', 'Ruang Teori 12 RPL', 1, 'Rusak')" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition mr-1" title="Edit Aset"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        <button type="button" onclick="deleteAsset(this, 'AST-TEO-01')" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus Aset"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                    </td>
                                </tr>
                                <tr class="inv-row hover:bg-[#F8FAFC]/80 transition" data-code="ast-gur-01" data-name="printer epson ecotank l3210" data-room="Ruang Guru & Kurikulum" data-condition="Baik">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">AST-GUR-01</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">Printer Epson EcoTank L3210 All-in-One</td>
                                    <td class="py-3.5 px-4 text-slate-600">Ruang Guru & Kurikulum</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-medium">2 Unit</td>
                                    <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">Baik</span></td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="editAsset('AST-GUR-01', 'Printer Epson EcoTank L3210 All-in-One', 'Ruang Guru & Kurikulum', 2, 'Baik')" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition mr-1" title="Edit Aset"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        <button type="button" onclick="deleteAsset(this, 'AST-GUR-01')" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus Aset"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                    </td>
                                </tr>
                                <tr class="inv-row hover:bg-[#F8FAFC]/80 transition" data-code="ast-lib-01" data-name="barcode scanner honeywell" data-room="Perpustakaan Utama" data-condition="Baik">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">AST-LIB-01</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">Barcode Scanner Laser Honeywell Voyager</td>
                                    <td class="py-3.5 px-4 text-slate-600">Perpustakaan Utama</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-medium">2 Unit</td>
                                    <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">Baik</span></td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="editAsset('AST-LIB-01', 'Barcode Scanner Laser Honeywell Voyager', 'Perpustakaan Utama', 2, 'Baik')" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition mr-1" title="Edit Aset"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        <button type="button" onclick="deleteAsset(this, 'AST-LIB-01')" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus Aset"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                    </td>
                                </tr>
                                <tr class="inv-row hover:bg-[#F8FAFC]/80 transition" data-code="ast-tlt-01" data-name="kran air stainless onda" data-room="Toilet Siswa Lt. 2" data-condition="Rusak">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">AST-TLT-01</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900">Kran Air Stainless Patah Onda 1/2 Inch</td>
                                    <td class="py-3.5 px-4 text-slate-600">Toilet Siswa Lt. 2</td>
                                    <td class="py-3.5 px-4 text-center font-mono font-medium">4 Unit</td>
                                    <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-rose-50 text-rose-800 border border-rose-200">Rusak</span></td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="editAsset('AST-TLT-01', 'Kran Air Stainless Patah Onda 1/2 Inch', 'Toilet Siswa Lt. 2', 4, 'Rusak')" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition mr-1" title="Edit Aset"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        <button type="button" onclick="deleteAsset(this, 'AST-TLT-01')" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus Aset"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>

        <!-- ======================================================== -->
        <!-- VIEW C: UTILITY (KHUSUS MASTER ADMIN)                   -->
        <!-- ======================================================== -->
        <div id="viewUtility" class="hidden flex flex-col flex-1">
            
            <!-- Header Halaman Utility & Sub-Tab Navigasi -->
            <header class="bg-white border-b border-[#E2E8F0] px-6 py-5 sticky top-0 z-20">
                <div class="max-w-7xl mx-auto space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] font-bold uppercase tracking-wider mb-1">
                                <i data-lucide="shield-check" class="w-3 h-3 stroke-[1.75]"></i>
                                Otorisasi Master Admin
                            </div>
                            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
                                Utility | Manajemen Pengguna & Peran
                            </h1>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Atur akun staf Sarpras, status persetujuan, pemblokiran lockout, dan hak akses peran sistem
                            </p>
                        </div>
                    </div>

                    <!-- Dua Tombol Sub-Tab: Pengguna (Default Aktif) & Peran -->
                    <div class="flex items-center gap-2 border-b border-[#E2E8F0] -mb-5 pt-1">
                        <button type="button" onclick="switchUtilitySubTab('pengguna')" id="tabBtnPengguna" 
                                class="px-4 py-2.5 text-xs font-bold border-b-2 border-[#0F5132] text-[#0F5132] transition flex items-center gap-2 cursor-pointer">
                            <i data-lucide="users" class="w-3.5 h-3.5 stroke-[1.75]"></i>
                            <span>Pengguna</span>
                        </button>
                        <button type="button" onclick="switchUtilitySubTab('peran')" id="tabBtnPeran" 
                                class="px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition flex items-center gap-2 cursor-pointer">
                            <i data-lucide="shield" class="w-3.5 h-3.5 stroke-[1.75]"></i>
                            <span>Peran</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Isi Halaman Utility -->
            <main class="max-w-7xl mx-auto px-6 py-8 w-full space-y-6 flex-grow">
                
                <!-- ---------------------------------------------------- -->
                <!-- SUB-TAB 1: PENGGUNA                                  -->
                <!-- ---------------------------------------------------- -->
                <div id="subTabPenggunaContent" class="space-y-4">
                    
                    <!-- Toolbar Aksi Pengguna -->
                    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 flex-1 max-w-lg">
                            <!-- Filter Dropdown Semua Pengguna -->
                            <select id="userFilterSelect" onchange="filterUserTable()" class="bg-white border border-[#E2E8F0] text-slate-800 text-xs rounded-xl px-3 py-2.5 font-medium focus:outline-none focus:border-[#0F5132]">
                                <option value="all">Semua Pengguna</option>
                                <option value="approved">Disetujui</option>
                                <option value="unapproved">Belum Disetujui</option>
                                <option value="locked">Terkunci Out</option>
                            </select>

                            <!-- Kolom Pencarian -->
                            <div class="relative flex-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                    <i data-lucide="search" class="w-3.5 h-3.5 stroke-[1.75]"></i>
                                </span>
                                <input type="text" id="userSearchInput" oninput="filterUserTable()" 
                                       placeholder="Cari nama pengguna, email, atau jabatan..." 
                                       class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl pl-8 pr-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#0F5132] transition">
                            </div>
                        </div>

                        <!-- Tombol + New User & Dropdown Tindakan -->
                        <div class="flex items-center gap-2">
                            <!-- Tombol + New User -->
                            <button type="button" onclick="openAddUserModal()" class="px-3.5 py-2 rounded-xl bg-[#0F5132] hover:bg-[#0b3d26] text-white text-xs font-bold flex items-center gap-2 transition active:scale-[0.98] shadow-xs cursor-pointer">
                                <i data-lucide="user-plus" class="w-4 h-4 stroke-[1.75]"></i>
                                <span>+ New User</span>
                            </button>

                            <!-- Dropdown Tindakan Massal -->
                            <div class="relative">
                                <button type="button" onclick="toggleBulkActionDropdown()" id="btnBulkActions" 
                                        class="px-3.5 py-2 rounded-xl border border-[#E2E8F0] bg-white hover:bg-[#F8FAFC] text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer">
                                    <span>Tindakan</span>
                                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 stroke-[1.75] text-slate-400"></i>
                                </button>
                                <div id="bulkActionsMenu" class="hidden absolute right-0 mt-1 w-48 rounded-xl bg-white shadow-xl border border-[#E2E8F0] py-1.5 z-30 text-xs">
                                    <button type="button" onclick="executeBulkAction('approve')" class="w-full px-3 py-2 text-left hover:bg-[#F8FAFC] flex items-center gap-2 text-emerald-800">
                                        <i data-lucide="check-check" class="w-3.5 h-3.5"></i> Setujui Terpilih
                                    </button>
                                    <button type="button" onclick="executeBulkAction('lock')" class="w-full px-3 py-2 text-left hover:bg-[#F8FAFC] flex items-center gap-2 text-amber-800">
                                        <i data-lucide="lock" class="w-3.5 h-3.5"></i> Kunci Out Terpilih
                                    </button>
                                    <button type="button" onclick="executeBulkAction('unlock')" class="w-full px-3 py-2 text-left hover:bg-[#F8FAFC] flex items-center gap-2 text-blue-800">
                                        <i data-lucide="unlock" class="w-3.5 h-3.5"></i> Buka Kunci Terpilih
                                    </button>
                                    <div class="my-1 border-t border-[#E2E8F0]"></div>
                                    <button type="button" onclick="executeBulkAction('delete')" class="w-full px-3 py-2 text-left hover:bg-rose-50 flex items-center gap-2 text-rose-700 font-semibold">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Terpilih
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Daftar Pengguna (9 Kolom Lengkap) -->
                    <div class="rounded-2xl border border-[#E2E8F0] bg-white overflow-hidden shadow-2xs">
                        <div class="overflow-x-auto">
                            <table class="borderless-vertical w-full text-left border-collapse" id="usersTable">
                                <thead>
                                    <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC] text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        <th class="py-3.5 px-3 text-center w-10">
                                            <input type="checkbox" id="selectAllUsers" onchange="toggleSelectAllUsers(this)" class="w-3.5 h-3.5 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </th>
                                        <th class="py-3.5 px-3 font-semibold">Nama Pengguna</th>
                                        <th class="py-3.5 px-3 font-semibold text-center">Apakah Disetujui</th>
                                        <th class="py-3.5 px-3 font-semibold">Email</th>
                                        <th class="py-3.5 px-3 font-semibold text-center">Apakah Dikunci Out</th>
                                        <th class="py-3.5 px-3 font-semibold whitespace-nowrap">Buat Tanggal</th>
                                        <th class="py-3.5 px-3 font-semibold whitespace-nowrap">Login Terakhir</th>
                                        <th class="py-3.5 px-3 font-semibold">Komentar / Jabatan</th>
                                        <th class="py-3.5 px-3 font-semibold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E2E8F0] text-xs text-slate-700" id="usersTbody">
                                    <!-- User 1 -->
                                    <tr class="user-row hover:bg-[#F8FAFC]/80 transition" data-id="1" data-name="kennan" data-email="kennan@smkn4bdg.sch.id" data-role="Master Admin" data-approved="true" data-locked="false">
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" class="user-checkbox w-3.5 h-3.5 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3">
                                            <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                                <span>Kennan</span>
                                                <span class="text-[9px] font-bold uppercase px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800">Master</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" checked onchange="toggleUserApproval(this, 'Kennan')" class="w-4 h-4 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer" title="Apakah Disetujui">
                                        </td>
                                        <td class="py-3.5 px-3 font-mono text-[11px] text-slate-600">kennan@smkn4bdg.sch.id</td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" onchange="toggleUserLockout(this, 'Kennan')" class="w-4 h-4 rounded text-rose-600 accent-rose-600 cursor-pointer" title="Apakah Dikunci Out">
                                        </td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-500">10 Jan 2026, 08:00</td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-700 font-semibold">24 Nov 2026, 09:45</td>
                                        <td class="py-3.5 px-3 text-slate-600">Master Admin & Lead Developer</td>
                                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                                            <button type="button" onclick="editUser(1, 'Kennan', 'kennan@smkn4bdg.sch.id', 'Master Admin & Lead Developer')" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition mr-0.5" title="Edit"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="resetUserPassword('Kennan', 'kennan@smkn4bdg.sch.id')" class="p-1.5 text-slate-400 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition mr-0.5" title="Reset Password"><i data-lucide="key" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="deleteUser(this, 'Kennan')" class="p-1.5 text-slate-400 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        </td>
                                    </tr>

                                    <!-- User 2 -->
                                    <tr class="user-row hover:bg-[#F8FAFC]/80 transition" data-id="2" data-name="drs. h. ali ridho, m.kom" data-email="ali.ridho@smkn4bdg.sch.id" data-role="Master Admin" data-approved="true" data-locked="false">
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" class="user-checkbox w-3.5 h-3.5 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3">
                                            <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                                <span>Drs. H. Ali Ridho, M.Kom</span>
                                                <span class="text-[9px] font-bold uppercase px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800">Master</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" checked onchange="toggleUserApproval(this, 'Drs. H. Ali Ridho, M.Kom')" class="w-4 h-4 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 font-mono text-[11px] text-slate-600">ali.ridho@smkn4bdg.sch.id</td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" onchange="toggleUserLockout(this, 'Drs. H. Ali Ridho, M.Kom')" class="w-4 h-4 rounded text-rose-600 accent-rose-600 cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-500">05 Jan 2026, 07:30</td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-700 font-semibold">24 Nov 2026, 08:15</td>
                                        <td class="py-3.5 px-3 text-slate-600">Kepala Sarana Prasarana & Pembina RPL</td>
                                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                                            <button type="button" onclick="editUser(2, 'Drs. H. Ali Ridho, M.Kom', 'ali.ridho@smkn4bdg.sch.id', 'Kepala Sarana Prasarana & Pembina RPL')" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition mr-0.5"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="resetUserPassword('Drs. H. Ali Ridho, M.Kom', 'ali.ridho@smkn4bdg.sch.id')" class="p-1.5 text-slate-400 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition mr-0.5"><i data-lucide="key" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="deleteUser(this, 'Drs. H. Ali Ridho, M.Kom')" class="p-1.5 text-slate-400 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        </td>
                                    </tr>

                                    <!-- User 3 -->
                                    <tr class="user-row hover:bg-[#F8FAFC]/80 transition" data-id="3" data-name="arne silviana, s.t., m.t." data-email="arne.silviana@smkn4bdg.sch.id" data-role="Master Admin" data-approved="true" data-locked="false">
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" class="user-checkbox w-3.5 h-3.5 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3">
                                            <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                                <span>Arne Silviana, S.T., M.T.</span>
                                                <span class="text-[9px] font-bold uppercase px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800">Master</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" checked onchange="toggleUserApproval(this, 'Arne Silviana, S.T., M.T.')" class="w-4 h-4 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 font-mono text-[11px] text-slate-600">arne.silviana@smkn4bdg.sch.id</td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" onchange="toggleUserLockout(this, 'Arne Silviana, S.T., M.T.')" class="w-4 h-4 rounded text-rose-600 accent-rose-600 cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-500">05 Jan 2026, 07:45</td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-700 font-semibold">23 Nov 2026, 14:20</td>
                                        <td class="py-3.5 px-3 text-slate-600">Koordinator Lab RPL & Kurikulum</td>
                                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                                            <button type="button" onclick="editUser(3, 'Arne Silviana, S.T., M.T.', 'arne.silviana@smkn4bdg.sch.id', 'Koordinator Lab RPL & Kurikulum')" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition mr-0.5"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="resetUserPassword('Arne Silviana, S.T., M.T.', 'arne.silviana@smkn4bdg.sch.id')" class="p-1.5 text-slate-400 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition mr-0.5"><i data-lucide="key" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="deleteUser(this, 'Arne Silviana, S.T., M.T.')" class="p-1.5 text-slate-400 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        </td>
                                    </tr>

                                    <!-- User 4 -->
                                    <tr class="user-row hover:bg-[#F8FAFC]/80 transition" data-id="4" data-name="budi santoso, a.md" data-email="budi.santoso@smkn4bdg.sch.id" data-role="Staf Sarpras" data-approved="true" data-locked="false">
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" class="user-checkbox w-3.5 h-3.5 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 font-semibold text-slate-900">Budi Santoso, A.Md</td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" checked onchange="toggleUserApproval(this, 'Budi Santoso, A.Md')" class="w-4 h-4 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 font-mono text-[11px] text-slate-600">budi.santoso@smkn4bdg.sch.id</td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" onchange="toggleUserLockout(this, 'Budi Santoso, A.Md')" class="w-4 h-4 rounded text-rose-600 accent-rose-600 cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-500">15 Jan 2026, 09:10</td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-700 font-semibold">24 Nov 2026, 07:50</td>
                                        <td class="py-3.5 px-3 text-slate-600">Teknisi Kelistrikan & Pendingin (AC)</td>
                                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                                            <button type="button" onclick="editUser(4, 'Budi Santoso, A.Md', 'budi.santoso@smkn4bdg.sch.id', 'Teknisi Kelistrikan & Pendingin (AC)')" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition mr-0.5"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="resetUserPassword('Budi Santoso, A.Md', 'budi.santoso@smkn4bdg.sch.id')" class="p-1.5 text-slate-400 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition mr-0.5"><i data-lucide="key" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="deleteUser(this, 'Budi Santoso, A.Md')" class="p-1.5 text-slate-400 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        </td>
                                    </tr>

                                    <!-- User 5 -->
                                    <tr class="user-row hover:bg-[#F8FAFC]/80 transition" data-id="5" data-name="rian pratama, s.kom" data-email="rian.pratama@smkn4bdg.sch.id" data-role="Staf Sarpras" data-approved="true" data-locked="false">
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" class="user-checkbox w-3.5 h-3.5 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 font-semibold text-slate-900">Rian Pratama, S.Kom</td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" checked onchange="toggleUserApproval(this, 'Rian Pratama, S.Kom')" class="w-4 h-4 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 font-mono text-[11px] text-slate-600">rian.pratama@smkn4bdg.sch.id</td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" onchange="toggleUserLockout(this, 'Rian Pratama, S.Kom')" class="w-4 h-4 rounded text-rose-600 accent-rose-600 cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-500">18 Jan 2026, 10:20</td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-700 font-semibold">22 Nov 2026, 11:35</td>
                                        <td class="py-3.5 px-3 text-slate-600">Teknisi Lab Komputer & Jaringan</td>
                                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                                            <button type="button" onclick="editUser(5, 'Rian Pratama, S.Kom', 'rian.pratama@smkn4bdg.sch.id', 'Teknisi Lab Komputer & Jaringan')" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition mr-0.5"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="resetUserPassword('Rian Pratama, S.Kom', 'rian.pratama@smkn4bdg.sch.id')" class="p-1.5 text-slate-400 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition mr-0.5"><i data-lucide="key" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="deleteUser(this, 'Rian Pratama, S.Kom')" class="p-1.5 text-slate-400 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        </td>
                                    </tr>

                                    <!-- User 6 -->
                                    <tr class="user-row hover:bg-[#F8FAFC]/80 transition" data-id="6" data-name="siti rahmawati" data-email="siti.rahma@smkn4bdg.sch.id" data-role="Staf Sarpras" data-approved="true" data-locked="false">
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" class="user-checkbox w-3.5 h-3.5 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 font-semibold text-slate-900">Siti Rahmawati</td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" checked onchange="toggleUserApproval(this, 'Siti Rahmawati')" class="w-4 h-4 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 font-mono text-[11px] text-slate-600">siti.rahma@smkn4bdg.sch.id</td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" onchange="toggleUserLockout(this, 'Siti Rahmawati')" class="w-4 h-4 rounded text-rose-600 accent-rose-600 cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-500">20 Jan 2026, 11:00</td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-700 font-semibold">24 Nov 2026, 08:40</td>
                                        <td class="py-3.5 px-3 text-slate-600">Staf Administrasi & Rekapitulasi</td>
                                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                                            <button type="button" onclick="editUser(6, 'Siti Rahmawati', 'siti.rahma@smkn4bdg.sch.id', 'Staf Administrasi & Rekapitulasi')" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition mr-0.5"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="resetUserPassword('Siti Rahmawati', 'siti.rahma@smkn4bdg.sch.id')" class="p-1.5 text-slate-400 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition mr-0.5"><i data-lucide="key" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="deleteUser(this, 'Siti Rahmawati')" class="p-1.5 text-slate-400 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        </td>
                                    </tr>

                                    <!-- User 7 (Pending Approval & Locked) -->
                                    <tr class="user-row hover:bg-[#F8FAFC]/80 transition" data-id="7" data-name="ahmad fauzi" data-email="ahmad.fauzi@smkn4bdg.sch.id" data-role="Staf Sarpras" data-approved="false" data-locked="true">
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" class="user-checkbox w-3.5 h-3.5 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 font-semibold text-slate-900">
                                            <div class="flex items-center gap-1.5">
                                                <span>Ahmad Fauzi</span>
                                                <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-amber-100 text-amber-800">Baru</span>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" onchange="toggleUserApproval(this, 'Ahmad Fauzi')" class="w-4 h-4 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 font-mono text-[11px] text-slate-600">ahmad.fauzi@smkn4bdg.sch.id</td>
                                        <td class="py-3.5 px-3 text-center">
                                            <input type="checkbox" checked onchange="toggleUserLockout(this, 'Ahmad Fauzi')" class="w-4 h-4 rounded text-rose-600 accent-rose-600 cursor-pointer">
                                        </td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-500">18 Feb 2026, 13:15</td>
                                        <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-400 italic">Belum Pernah</td>
                                        <td class="py-3.5 px-3 text-slate-600">Staf Magang Teknisi Jaringan</td>
                                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                                            <button type="button" onclick="editUser(7, 'Ahmad Fauzi', 'ahmad.fauzi@smkn4bdg.sch.id', 'Staf Magang Teknisi Jaringan')" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition mr-0.5"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="resetUserPassword('Ahmad Fauzi', 'ahmad.fauzi@smkn4bdg.sch.id')" class="p-1.5 text-slate-400 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition mr-0.5"><i data-lucide="key" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                            <button type="button" onclick="deleteUser(this, 'Ahmad Fauzi')" class="p-1.5 text-slate-400 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- ---------------------------------------------------- -->
                <!-- SUB-TAB 2: PERAN                                     -->
                <!-- ---------------------------------------------------- -->
                <div id="subTabPeranContent" class="hidden space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Role 1: Master Admin -->
                        <div class="bg-white rounded-2xl border-2 border-[#0F5132]/30 p-6 shadow-xs space-y-4 relative">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#0F5132] flex items-center justify-center font-bold">
                                        <i data-lucide="shield-check" class="w-5 h-5 stroke-[1.75]"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Master Admin</h3>
                                        <span class="text-[11px] text-slate-400">Tingkat Hak Akses Tertinggi</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 font-mono">
                                    3 Anggota Aktif
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed">
                                Memiliki otoritas tak terbatas untuk mengelola sistem Sarpras sekolah, manajemen pengguna & peran (Utility), konfigurasi stored procedure, ekspor data rekapitulasi, dan audit trail perbaikan.
                            </p>

                            <div class="space-y-2 pt-2 border-t border-[#E2E8F0] text-xs">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Izin Akses Khusus:</span>
                                <div class="grid grid-cols-1 gap-1.5 text-slate-700">
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Utility & Manajemen Pengguna (Full CRUD)</div>
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Kelola Master Inventaris Barang (Tambah, Edit, Hapus)</div>
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Buat & Cetak Kode QR Pintu Tiap Ruangan</div>
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Verifikasi, Penolakan, & Penyelesaian Tiket Kerusakan</div>
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Ekspor Rekapitulasi Laporan (PDF, Excel, Print)</div>
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Eksekusi Prosedur Oracle APEX (sp_selesaikan_perbaikan)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Role 2: Staf / Teknisi Sarpras -->
                        <div class="bg-white rounded-2xl border border-[#E2E8F0] p-6 shadow-xs space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                                        <i data-lucide="wrench" class="w-5 h-5 stroke-[1.75]"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Staf / Teknisi Sarpras</h3>
                                        <span class="text-[11px] text-slate-400">Tingkat Hak Akses Operasional Lapangan</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 font-mono">
                                    4 Anggota Aktif
                                </span>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed">
                                Dikhususkan bagi staf pemeliharaan dan teknisi lapangan untuk memeriksa tiket laporan masuk, memperbarui status perbaikan pengerjaan, mengunggah bukti foto selesai, dan input biaya perbaikan.
                            </p>

                            <div class="space-y-2 pt-2 border-t border-[#E2E8F0] text-xs">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Izin Akses Operasional:</span>
                                <div class="grid grid-cols-1 gap-1.5 text-slate-700">
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Meninjau Daftar Laporan Kerusakan Masuk</div>
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Update Status Pengerjaan (Menunggu &rarr; Diproses &rarr; Selesai)</div>
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Mengunggah Foto Bukti Hasil Perbaikan & Biaya</div>
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Melihat Data Master Inventaris Barang (Read Only)</div>
                                    <div class="flex items-center gap-2"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> Mencetak Stiker QR Code Ruangan</div>
                                    <div class="flex items-center gap-2 text-slate-400"><i data-lucide="x" class="w-3.5 h-3.5 text-slate-300"></i> Akses Utility & Manajemen Pengguna (Dibatasi)</div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </main>
        </div>

    </div>

    <!-- ======================================================== -->
    <!-- MODALS & DRAWERS                                         -->
    <!-- ======================================================== -->

    <!-- DRAWER / MODAL DETAIL & PROSES LAPORAN (Stripe Style Slide-over) -->
    <div id="detailDrawerModal" class="hidden fixed inset-0 z-50 overflow-hidden bg-slate-900/40 backdrop-blur-xs flex justify-end transition-opacity">
        <div class="w-full max-w-lg bg-white h-full shadow-2xl flex flex-col justify-between overflow-y-auto border-l border-[#E2E8F0]">
            
            <div class="p-6 border-b border-[#E2E8F0] flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <span id="drawerCode" class="text-xs font-mono font-bold text-slate-400 block">#SRP-000</span>
                    <h2 id="drawerTitle" class="text-base font-bold text-slate-900 mt-0.5 leading-snug">Rincian Laporan</h2>
                </div>
                <button type="button" onclick="closeDetailDrawer()" class="p-2 rounded-xl text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5 stroke-[1.75]"></i>
                </button>
            </div>

            <div class="p-6 space-y-5 text-xs flex-grow">
                <div class="flex items-center justify-between p-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0]">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Tingkat Urgensi</span>
                        <span id="drawerPriority" class="text-xs font-bold text-slate-800">Sedang</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Dukungan Laporan</span>
                        <span id="drawerUpvotes" class="text-xs font-mono font-bold text-[#0F5132]">+1 Pelapor</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Status Saat Ini</span>
                        <span id="drawerCurrentStatus" class="text-xs font-bold text-slate-800">Menunggu</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl border border-[#E2E8F0]">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Pelapor</span>
                        <p id="drawerReporter" class="font-bold text-slate-800 mt-0.5">-</p>
                        <p id="drawerReporterInfo" class="text-[11px] text-slate-500 mt-0.5">-</p>
                    </div>
                    <div class="p-3 rounded-xl border border-[#E2E8F0]">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Ruangan & Aset</span>
                        <p id="drawerRoom" class="font-bold text-slate-800 mt-0.5">-</p>
                        <p id="drawerAsset" class="text-[11px] text-slate-500 mt-0.5">-</p>
                    </div>
                </div>

                <div>
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block mb-1">Deskripsi Keluhan</span>
                    <p id="drawerDescription" class="p-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-slate-700 leading-relaxed"></p>
                </div>

                <div>
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block mb-2">Dokumentasi Foto Bukti</span>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="text-[10px] text-slate-500 block mb-1 font-medium">Sebelum Perbaikan:</span>
                            <div class="rounded-xl overflow-hidden border border-[#E2E8F0] bg-slate-100 aspect-video flex items-center justify-center">
                                <img id="drawerPhotoBefore" src="#" alt="Foto Sebelum" class="w-full h-full object-cover hidden">
                                <span id="drawerNoBefore" class="text-[10px] text-slate-400">Tidak ada foto</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] text-emerald-700 block mb-1 font-medium">Sesudah Perbaikan:</span>
                            <div class="rounded-xl overflow-hidden border border-[#E2E8F0] bg-emerald-50/50 aspect-video flex items-center justify-center">
                                <img id="drawerPhotoAfter" src="#" alt="Foto Sesudah" class="w-full h-full object-cover hidden">
                                <span id="drawerNoAfter" class="text-[10px] text-slate-400">Belum diunggah</span>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="drawerActionForm" onsubmit="submitDrawerStatus(event)" class="pt-4 border-t border-[#E2E8F0] space-y-3.5">
                    <input type="hidden" id="drawerTicketId" name="ticket_id" value="">

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1.5">
                            Perbarui Status Pengerjaan
                        </label>
                        <select id="drawerStatusSelect" name="status" onchange="handleStatusChange(this.value)" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs font-semibold rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#0F5132] focus:ring-1 focus:ring-[#0F5132] transition">
                            <option value="Menunggu">Menunggu (Pending)</option>
                            <option value="Diproses">Diproses (In Progress)</option>
                            <option value="Selesai">Selesai (Resolved)</option>
                            <option value="Ditolak">Ditolak (Rejected)</option>
                        </select>
                    </div>

                    <div id="costInputWrapper">
                        <label class="block text-xs font-semibold text-slate-800 mb-1.5">
                            Biaya Perbaikan (Rp)
                        </label>
                        <input type="number" id="drawerRepairCost" name="repair_cost" value="0" min="0" step="1000" 
                            class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs font-mono rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#0F5132] transition" placeholder="0">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1.5">
                            Catatan Tindakan Teknisi
                        </label>
                        <textarea id="drawerNote" name="note" rows="2" 
                            class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#0F5132] transition resize-none" 
                            placeholder="Tuliskan tindakan teknisi yang telah atau sedang dilakukan..."></textarea>
                    </div>

                    <div id="photoAfterInputWrapper">
                        <label class="block text-xs font-semibold text-slate-800 mb-1.5">
                            Unggah Foto Bukti Selesai
                        </label>
                        <input type="file" id="drawerPhotoAfterInput" name="photo_after" accept="image/*" 
                            class="w-full text-xs text-slate-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                    </div>

                    <div class="pt-2">
                        <button type="submit" id="btnSaveDrawer" class="w-full py-2.5 px-4 rounded-xl font-bold text-xs text-white bg-[#0F5132] hover:bg-[#0b3d26] transition active:scale-[0.99] shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                            <i data-lucide="check" class="w-4 h-4 stroke-[1.75]"></i>
                            <span>Simpan Perubahan Laporan</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- MODAL GENERATE QR CODE RUANGAN -->
    <div id="qrGeneratorModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-[#E2E8F0]">
            
            <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-[#0F5132]/10 text-[#0F5132] flex items-center justify-center">
                        <i data-lucide="qr-code" class="w-4 h-4 stroke-[1.75]"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">QR Code Ruangan</h3>
                        <p class="text-[11px] text-slate-500">Cetak dan tempel pada pintu ruangan</p>
                    </div>
                </div>
                <button type="button" onclick="closeQrModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-800 cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4 stroke-[1.75]"></i>
                </button>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <label class="block text-xs font-semibold text-slate-800 mb-1">Pilih Ruangan</label>
                    <select id="qrRoomSelect" onchange="updateQrDisplay(this.value)" class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 font-medium">
                        <option value="1">Lab Komputer RPL 1 (ID: 1)</option>
                        <option value="2">Lab Jaringan TKJ (ID: 2)</option>
                        <option value="3">Ruang Guru & Kurikulum (ID: 3)</option>
                        <option value="4">Perpustakaan Utama (ID: 4)</option>
                        <option value="5">Toilet Siswa Lt. 2 (ID: 5)</option>
                        <option value="6">Ruang Teori 12 RPL (ID: 6)</option>
                        <option value="7">Aula Sekolah (ID: 7)</option>
                    </select>
                </div>

                <div id="printableQrArea" class="border-2 border-dashed border-[#E2E8F0] rounded-2xl p-5 text-center bg-[#F8FAFC] space-y-2">
                    <div class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">SMK NEGERI 4 BANDUNG</div>
                    <div class="font-extrabold text-sm text-slate-900" id="qrRoomTitle">Lab Komputer RPL 1</div>
                    <div class="text-[10px] text-slate-500">Pindai QR ini untuk melapor kerusakan di ruangan ini</div>
                    
                    <div class="w-44 h-44 mx-auto bg-white p-2.5 rounded-xl border border-[#E2E8F0] shadow-2xs flex items-center justify-center my-2">
                        <img id="qrImageElement" src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=http://localhost:8000/lapor/smkn-4-bandung?ruangan=1" alt="QR Code Pintu" class="w-full h-full object-contain">
                    </div>

                    <div class="text-[10px] font-mono text-slate-400" id="qrUrlText">
                        http://localhost:8000/lapor/smkn-4-bandung?ruangan=1
                    </div>
                </div>
            </div>

            <div class="pt-2 flex gap-2">
                <button type="button" onclick="printQrCard()" class="flex-1 py-2.5 px-4 rounded-xl font-bold text-xs text-white bg-[#0F5132] hover:bg-[#0b3d26] transition active:scale-[0.98] shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="printer" class="w-3.5 h-3.5 stroke-[1.75]"></i>
                    <span>Cetak Stiker Pintu</span>
                </button>
                <button type="button" onclick="closeQrModal()" class="py-2.5 px-4 rounded-xl border border-[#E2E8F0] hover:bg-slate-50 text-slate-700 font-semibold text-xs cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- MODAL TAMBAH / EDIT BARANG INVENTARIS -->
    <div id="assetModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-[#E2E8F0]">
            <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-[#0F5132]/10 text-[#0F5132] flex items-center justify-center">
                        <i data-lucide="package-plus" class="w-4 h-4 stroke-[1.75]"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900" id="assetModalTitle">Tambah Aset Baru</h3>
                        <p class="text-[11px] text-slate-500">Daftarkan inventaris pada master fasilitas</p>
                    </div>
                </div>
                <button type="button" onclick="closeAssetModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-800 cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4 stroke-[1.75]"></i>
                </button>
            </div>

            <form id="assetForm" onsubmit="handleAssetSubmit(event)" class="space-y-3.5 text-xs">
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Kode Aset</label>
                    <input type="text" id="assetCode" required placeholder="Contoh: AST-RPL-05" 
                           class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 font-mono uppercase focus:bg-white focus:outline-none focus:border-[#0F5132]">
                </div>
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Nama Barang / Aset</label>
                    <input type="text" id="assetName" required placeholder="Contoh: UPS APC 1500VA" 
                           class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#0F5132]">
                </div>
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Ruangan Lokasi</label>
                    <select id="assetRoom" required class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 font-medium">
                        <option value="Lab Komputer RPL 1">Lab Komputer RPL 1</option>
                        <option value="Lab Jaringan TKJ">Lab Jaringan TKJ</option>
                        <option value="Ruang Guru & Kurikulum">Ruang Guru & Kurikulum</option>
                        <option value="Perpustakaan Utama">Perpustakaan Utama</option>
                        <option value="Toilet Siswa Lt. 2">Toilet Siswa Lt. 2</option>
                        <option value="Ruang Teori 12 RPL">Ruang Teori 12 RPL</option>
                        <option value="Aula Sekolah">Aula Sekolah</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-800 mb-1">Jumlah Unit</label>
                        <input type="number" id="assetQuantity" required min="1" value="1" 
                               class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 font-mono focus:bg-white focus:outline-none focus:border-[#0F5132]">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-800 mb-1">Kondisi</label>
                        <select id="assetCondition" required class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 font-medium">
                            <option value="Baik">Baik</option>
                            <option value="Perbaikan">Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </div>

                <div class="pt-3 flex gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 rounded-xl font-bold text-xs text-white bg-[#0F5132] hover:bg-[#0b3d26] transition active:scale-[0.98] shadow-xs cursor-pointer">
                        Simpan Aset
                    </button>
                    <button type="button" onclick="closeAssetModal()" class="py-2.5 px-4 rounded-xl border border-[#E2E8F0] hover:bg-slate-50 text-slate-700 font-semibold text-xs cursor-pointer">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH USER (UTILITY) -->
    <div id="userModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-[#E2E8F0]">
            <div class="flex items-center justify-between border-b border-[#E2E8F0] pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-[#0F5132]/10 text-[#0F5132] flex items-center justify-center">
                        <i data-lucide="user-plus" class="w-4 h-4 stroke-[1.75]"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900" id="userModalTitle">Tambah Akun Pengguna</h3>
                        <p class="text-[11px] text-slate-500">Otorisasi akun staf / admin baru</p>
                    </div>
                </div>
                <button type="button" onclick="closeUserModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-800 cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4 stroke-[1.75]"></i>
                </button>
            </div>

            <form id="userForm" onsubmit="handleUserSubmit(event)" class="space-y-3.5 text-xs">
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Nama Lengkap Pengguna</label>
                    <input type="text" id="userFullName" required placeholder="Contoh: Fajar Hidayat, S.Pd" 
                           class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#0F5132]">
                </div>
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Email Dinas / Staf</label>
                    <input type="email" id="userEmail" required placeholder="fajar.hidayat@smkn4bdg.sch.id" 
                           class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#0F5132]">
                </div>
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Peran Akun</label>
                    <select id="userRole" required class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 font-medium">
                        <option value="Staf Sarpras">Staf / Teknisi Sarpras</option>
                        <option value="Master Admin">Master Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Komentar / Jabatan Staf</label>
                    <input type="text" id="userDepartment" required placeholder="Contoh: Teknisi Audio Visual & Gedung" 
                           class="w-full bg-[#F8FAFC] border border-[#E2E8F0] text-slate-900 text-xs rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:border-[#0F5132]">
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="userIsApproved" checked class="w-4 h-4 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                    <label for="userIsApproved" class="text-xs text-slate-700 font-medium cursor-pointer">Setujui akun ini secara langsung (Status Aktif)</label>
                </div>

                <div class="pt-3 flex gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 rounded-xl font-bold text-xs text-white bg-[#0F5132] hover:bg-[#0b3d26] transition active:scale-[0.98] shadow-xs cursor-pointer">
                        Simpan Pengguna
                    </button>
                    <button type="button" onclick="closeUserModal()" class="py-2.5 px-4 rounded-xl border border-[#E2E8F0] hover:bg-slate-50 text-slate-700 font-semibold text-xs cursor-pointer">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT ENGINE LENGKAP -->
    <script>
        // State Global
        let activeMainView = 'reports'; // 'reports' | 'inventory' | 'utility'
        let currentUserRole = localStorage.getItem('sarpras_user_role') || 'Master Admin';
        let isSidebarCollapsed = localStorage.getItem('sarpras_sidebar_collapsed') === 'true';

        // Filter State Laporan
        let activeStatusFilter = 'Semua';
        let activePriorityFilter = 'all';
        let activeRoomFilter = 'all';
        let activeDateFilter = 'all';
        let isSortUpvotesDesc = false;

        document.addEventListener('DOMContentLoaded', function () {
            // Restore Sidebar State
            applySidebarState(isSidebarCollapsed);

            // Restore User Role State
            applyUserRole(currentUserRole);

            // Read hash if available
            const hash = window.location.hash.replace('#', '');
            if (['reports', 'inventory', 'utility'].includes(hash)) {
                if (hash === 'utility' && currentUserRole !== 'Master Admin') {
                    switchMainView('reports');
                } else {
                    switchMainView(hash);
                }
            }

            if (window.lucide) {
                lucide.createIcons();
            }
        });

        // ----------------------------------------------------
        // 1. COLLAPSIBLE SIDEBAR
        // ----------------------------------------------------
        function toggleSidebar() {
            isSidebarCollapsed = !isSidebarCollapsed;
            localStorage.setItem('sarpras_sidebar_collapsed', isSidebarCollapsed);
            applySidebarState(isSidebarCollapsed);
        }

        function applySidebarState(collapsed) {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('sidebarToggleIcon');

            if (collapsed) {
                sidebar.classList.add('collapsed');
                toggleIcon.setAttribute('data-lucide', 'panel-left-open');
            } else {
                sidebar.classList.remove('collapsed');
                toggleIcon.setAttribute('data-lucide', 'panel-left-close');
            }

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        // ----------------------------------------------------
        // 2. USER ROLE DEMO SWITCHER (Master Admin vs Teknisi)
        // ----------------------------------------------------
        function toggleUserRoleDemo() {
            if (currentUserRole === 'Master Admin') {
                currentUserRole = 'Teknisi Sarpras';
            } else {
                currentUserRole = 'Master Admin';
            }
            localStorage.setItem('sarpras_user_role', currentUserRole);
            applyUserRole(currentUserRole);

            // Jika user ada di view utility dan role bukan master admin, kembalikan ke reports
            if (currentUserRole !== 'Master Admin' && activeMainView === 'utility') {
                switchMainView('reports');
            }
        }

        function applyUserRole(role) {
            const roleLabel = document.getElementById('sidebarRoleLabel');
            const navUtility = document.getElementById('navItemUtility');

            if (roleLabel) {
                roleLabel.innerText = role;
                if (role === 'Master Admin') {
                    roleLabel.className = 'text-[10px] font-semibold text-emerald-700 truncate';
                } else {
                    roleLabel.className = 'text-[10px] font-semibold text-slate-500 truncate';
                }
            }

            // Aturan: Menu Utility hanya ditampilkan jika login sebagai Master Admin
            if (navUtility) {
                if (role === 'Master Admin') {
                    navUtility.style.display = 'flex';
                } else {
                    navUtility.style.display = 'none';
                }
            }
        }

        // ----------------------------------------------------
        // 3. SPA VIEW SWITCHER (Laporan, Inventaris, Utility)
        // ----------------------------------------------------
        function switchMainView(viewName) {
            if (viewName === 'utility' && currentUserRole !== 'Master Admin') {
                alert('Akses Terbatas: Menu Utility hanya dapat diakses oleh Master Admin.');
                return;
            }

            activeMainView = viewName;
            window.location.hash = viewName;

            const viewReports = document.getElementById('viewReports');
            const viewInventory = document.getElementById('viewInventory');
            const viewUtility = document.getElementById('viewUtility');

            viewReports.classList.add('hidden');
            viewInventory.classList.add('hidden');
            viewUtility.classList.add('hidden');

            // Reset highlight nav buttons
            const navRingkasan = document.getElementById('navItemRingkasan');
            const navReports = document.getElementById('navItemReports');
            const navInventory = document.getElementById('navItemInventory');
            const navUtility = document.getElementById('navItemUtility');

            [navRingkasan, navReports, navInventory, navUtility].forEach(item => {
                if (!item) return;
                item.className = 'sidebar-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-[#F8FAFC] transition group cursor-pointer';
                const icon = item.querySelector('i');
                if (icon) {
                    icon.classList.remove('text-[#0F5132]');
                    icon.classList.add('text-slate-400');
                }
            });

            if (viewName === 'reports') {
                viewReports.classList.remove('hidden');
                navReports.className = 'sidebar-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold bg-[#0F5132]/10 text-[#0F5132] transition group cursor-pointer';
                const icon = navReports.querySelector('i');
                if (icon) {
                    icon.classList.add('text-[#0F5132]');
                    icon.classList.remove('text-slate-400');
                }
            } else if (viewName === 'inventory') {
                viewInventory.classList.remove('hidden');
                navInventory.className = 'sidebar-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold bg-[#0F5132]/10 text-[#0F5132] transition group cursor-pointer';
                const icon = navInventory.querySelector('i');
                if (icon) {
                    icon.classList.add('text-[#0F5132]');
                    icon.classList.remove('text-slate-400');
                }
                filterInventoryTable();
            } else if (viewName === 'utility') {
                viewUtility.classList.remove('hidden');
                navUtility.className = 'sidebar-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold bg-[#0F5132]/10 text-[#0F5132] transition group cursor-pointer';
                const icon = navUtility.querySelector('i');
                if (icon) {
                    icon.classList.add('text-[#0F5132]');
                    icon.classList.remove('text-slate-400');
                }
                filterUserTable();
            }

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function showOverviewTab() {
            switchMainView('reports');
            filterStatus('Semua');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ----------------------------------------------------
        // 4. FILTER STATUS SUMMARY CARDS & SEARCH LAPORAN
        // ----------------------------------------------------
        function filterStatus(status, buttonEl) {
            activeStatusFilter = status;
            document.querySelectorAll('.status-card').forEach(card => card.classList.remove('active'));

            if (buttonEl) {
                buttonEl.classList.add('active');
            } else {
                document.querySelectorAll('.status-card').forEach(card => {
                    if (card.querySelector('span').innerText.includes(status)) {
                        card.classList.add('active');
                    }
                });
            }

            applyFilters();
        }

        function applyFilters() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase().trim();
            const rows = Array.from(document.querySelectorAll('#ticketsTbody tr.ticket-row'));
            let visible = 0;

            rows.forEach(row => {
                const code = row.getAttribute('data-code') || '';
                const room = row.getAttribute('data-room') || '';
                const asset = row.getAttribute('data-asset') || '';
                const title = row.getAttribute('data-title') || '';
                const reporter = row.getAttribute('data-reporter') || '';
                const priority = row.getAttribute('data-priority') || '';
                const status = row.getAttribute('data-status') || '';

                const matchesSearch = !searchQuery || 
                    code.includes(searchQuery) || 
                    room.includes(searchQuery) || 
                    asset.includes(searchQuery) || 
                    title.includes(searchQuery) || 
                    reporter.includes(searchQuery);

                let matchesStatus = (activeStatusFilter === 'Semua');
                if (!matchesStatus) {
                    if (activeStatusFilter === 'Menunggu') {
                        matchesStatus = (status === 'Menunggu' || status === 'Pending');
                    } else if (activeStatusFilter === 'Diproses') {
                        matchesStatus = (status === 'Diproses' || status === 'In Progress' || status === 'Dalam Pengerjaan');
                    } else if (activeStatusFilter === 'Selesai') {
                        matchesStatus = (status === 'Selesai' || status === 'Resolved');
                    } else if (activeStatusFilter === 'Ditolak') {
                        matchesStatus = (status === 'Ditolak' || status === 'Rejected');
                    }
                }

                let matchesPriority = (activePriorityFilter === 'all') || (priority.toLowerCase() === activePriorityFilter.toLowerCase());
                let matchesRoom = (activeRoomFilter === 'all') || room.includes(activeRoomFilter.toLowerCase());

                if (matchesSearch && matchesStatus && matchesPriority && matchesRoom) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('visibleCount').innerText = visible;

            const hasActiveFilter = (activeStatusFilter !== 'Semua') || (activePriorityFilter !== 'all') || (activeRoomFilter !== 'all') || searchQuery;
            const resetBtn = document.getElementById('btnResetFilters');
            if (hasActiveFilter) {
                resetBtn.classList.remove('hidden');
            } else {
                resetBtn.classList.add('hidden');
            }
        }

        function toggleDateFilter() {
            document.getElementById('dateFilterDropdown').classList.toggle('hidden');
        }
        function toggleRoomFilter() {
            document.getElementById('roomFilterDropdown').classList.toggle('hidden');
        }
        function togglePriorityFilter() {
            document.getElementById('priorityFilterDropdown').classList.toggle('hidden');
        }
        function toggleExportDropdown() {
            document.getElementById('exportDropdownMenu').classList.toggle('hidden');
        }

        function setDateFilter(val, label) {
            activeDateFilter = val;
            document.getElementById('dateFilterLabel').innerText = label;
            document.getElementById('dateFilterDropdown').classList.add('hidden');
            applyFilters();
        }

        function setRoomFilter(val, label) {
            activeRoomFilter = val;
            document.getElementById('roomFilterLabel').innerText = label;
            document.getElementById('roomFilterDropdown').classList.add('hidden');
            applyFilters();
        }

        function setPriorityFilter(val, label) {
            activePriorityFilter = val;
            document.getElementById('priorityFilterLabel').innerText = label;
            document.getElementById('priorityFilterDropdown').classList.add('hidden');
            applyFilters();
        }

        function filterByPriority(val) {
            setPriorityFilter(val, val);
        }

        function toggleSortUpvote() {
            isSortUpvotesDesc = !isSortUpvotesDesc;
            const tbody = document.getElementById('ticketsTbody');
            const rows = Array.from(tbody.querySelectorAll('tr.ticket-row'));

            rows.sort((a, b) => {
                const upA = parseInt(a.getAttribute('data-upvotes') || '1');
                const upB = parseInt(b.getAttribute('data-upvotes') || '1');
                return isSortUpvotesDesc ? (upB - upA) : (upA - upB);
            });

            rows.forEach(row => tbody.appendChild(row));

            const btn = document.getElementById('btnSortUpvote');
            const lbl = document.getElementById('sortLabel');
            if (isSortUpvotesDesc) {
                btn.classList.add('border-[#0F5132]', 'bg-[#0F5132]/10', 'text-[#0F5132]');
                lbl.innerText = 'Urutkan: Terbanyak Upvote';
            } else {
                btn.classList.remove('border-[#0F5132]', 'bg-[#0F5132]/10', 'text-[#0F5132]');
                lbl.innerText = '+ Urutkan (Terbanyak +1 Upvote)';
            }
        }

        function resetAllFilters() {
            document.getElementById('searchInput').value = '';
            activeStatusFilter = 'Semua';
            activePriorityFilter = 'all';
            activeRoomFilter = 'all';
            activeDateFilter = 'all';
            document.getElementById('dateFilterLabel').innerText = '+ Tanggal';
            document.getElementById('roomFilterLabel').innerText = '+ Ruangan';
            document.getElementById('priorityFilterLabel').innerText = '+ Tingkat Urgensi';

            document.querySelectorAll('.status-card').forEach(card => card.classList.remove('active'));
            document.querySelector('.status-card').classList.add('active');

            applyFilters();
        }

        // ----------------------------------------------------
        // 5. DRAWER DETAIL & PROSES LAPORAN
        // ----------------------------------------------------
        function openDetailDrawer(ticket) {
            document.getElementById('drawerCode').innerText = ticket.ticket_code || ('#' + ticket.id);
            document.getElementById('drawerTitle').innerText = ticket.title || 'Laporan Kerusakan';
            document.getElementById('drawerTicketId').value = ticket.id;

            document.getElementById('drawerPriority').innerText = ticket.priority || 'Sedang';
            document.getElementById('drawerUpvotes').innerText = `+${ticket.upvotes || 1} Pelapor`;
            document.getElementById('drawerCurrentStatus').innerText = ticket.status || 'Menunggu';

            document.getElementById('drawerReporter').innerText = ticket.reporter_name || '-';
            document.getElementById('drawerReporterInfo').innerText = ticket.reporter_info || '-';
            document.getElementById('drawerRoom').innerText = ticket.room_name || '-';
            document.getElementById('drawerAsset').innerText = ticket.asset_name || 'Non-Aset';
            document.getElementById('drawerDescription').innerText = ticket.description || 'Tidak ada keterangan tambahan.';

            const imgB = document.getElementById('drawerPhotoBefore');
            const noB = document.getElementById('drawerNoBefore');
            if (ticket.photo_before) {
                const bUrl = ticket.photo_before.startsWith('http') ? ticket.photo_before : `/storage/${ticket.photo_before}`;
                imgB.src = bUrl;
                imgB.classList.remove('hidden');
                noB.classList.add('hidden');
            } else {
                imgB.classList.add('hidden');
                noB.classList.remove('hidden');
            }

            const imgA = document.getElementById('drawerPhotoAfter');
            const noA = document.getElementById('drawerNoAfter');
            if (ticket.photo_after) {
                const aUrl = ticket.photo_after.startsWith('http') ? ticket.photo_after : `/storage/${ticket.photo_after}`;
                imgA.src = aUrl;
                imgA.classList.remove('hidden');
                noA.classList.add('hidden');
            } else {
                imgA.classList.add('hidden');
                noA.classList.remove('hidden');
            }

            document.getElementById('drawerStatusSelect').value = ticket.status || 'Menunggu';
            document.getElementById('drawerRepairCost').value = ticket.repair_cost || 0;
            document.getElementById('drawerNote').value = ticket.note || '';

            document.getElementById('detailDrawerModal').classList.remove('hidden');
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function closeDetailDrawer() {
            document.getElementById('detailDrawerModal').classList.add('hidden');
        }

        function handleStatusChange(status) {
            if (status === 'Selesai') {
                const cost = document.getElementById('drawerRepairCost');
                if (parseInt(cost.value) === 0) {
                    cost.value = 50000;
                }
            }
        }

        function submitDrawerStatus(e) {
            e.preventDefault();
            const ticketId = document.getElementById('drawerTicketId').value;
            const status = document.getElementById('drawerStatusSelect').value;
            const cost = document.getElementById('drawerRepairCost').value;
            const note = document.getElementById('drawerNote').value;
            const fileInput = document.getElementById('drawerPhotoAfterInput');

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('status', status);
            formData.append('repair_cost', cost);
            formData.append('note', note);
            if (fileInput.files[0]) {
                formData.append('photo_after', fileInput.files[0]);
            }

            const btn = document.getElementById('btnSaveDrawer');
            btn.disabled = true;
            btn.innerHTML = '<span>Menyimpan...</span>';

            fetch(`/sarpras/status/${ticketId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(`Laporan #${ticketId} berhasil diperbarui menjadi status: ${status}`);
                window.location.reload();
            })
            .catch(err => {
                document.getElementById('drawerActionForm').action = `/sarpras/status/${ticketId}`;
                document.getElementById('drawerActionForm').method = 'POST';
                document.getElementById('drawerActionForm').submit();
            });
        }

        // ----------------------------------------------------
        // 6. MODAL QR CODE RUANGAN
        // ----------------------------------------------------
        const roomQrTitles = {
            1: 'Lab Komputer RPL 1',
            2: 'Lab Jaringan TKJ',
            3: 'Ruang Guru & Kurikulum',
            4: 'Perpustakaan Utama',
            5: 'Toilet Siswa Lt. 2',
            6: 'Ruang Teori 12 RPL',
            7: 'Aula Sekolah'
        };

        function openQrModal(roomId = 1) {
            document.getElementById('qrRoomSelect').value = roomId;
            updateQrDisplay(roomId);
            document.getElementById('qrGeneratorModal').classList.remove('hidden');
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function closeQrModal() {
            document.getElementById('qrGeneratorModal').classList.add('hidden');
        }

        function updateQrDisplay(roomId) {
            const roomName = roomQrTitles[roomId] || 'Ruangan Sekolah';
            document.getElementById('qrRoomTitle').innerText = roomName;
            const targetUrl = `${window.location.origin}/lapor/smkn-4-bandung?ruangan=${roomId}`;
            document.getElementById('qrUrlText').innerText = targetUrl;
            document.getElementById('qrImageElement').src = `https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${encodeURIComponent(targetUrl)}`;
        }

        function printQrCard() {
            const printContent = document.getElementById('printableQrArea').innerHTML;
            const w = window.open('', '_blank', 'width=450,height=550');
            w.document.write(`
                <html>
                <head>
                    <title>Cetak QR Code Pintu - Sarpras SMKN 4</title>
                    <style>
                        body { font-family: sans-serif; text-align: center; padding: 30px; }
                        .card { border: 2px solid #000; padding: 25px; border-radius: 15px; }
                        img { margin: 15px 0; width: 180px; height: 180px; }
                    </style>
                </head>
                <body onload="window.print()">
                    <div class="card">${printContent}</div>
                </body>
                </html>
            `);
            w.document.close();
        }

        // ----------------------------------------------------
        // 7. INVENTARIS ENGINE (FILTER, TAMBAH, EDIT, HAPUS)
        // ----------------------------------------------------
        function filterInventoryTable() {
            const query = (document.getElementById('inventorySearchInput').value || '').toLowerCase().trim();
            const room = document.getElementById('inventoryRoomSelect').value;
            const cond = document.getElementById('inventoryConditionSelect').value;

            const rows = Array.from(document.querySelectorAll('#inventoryTbody tr.inv-row'));
            let total = 0, baik = 0, perbaikan = 0, rusak = 0;

            rows.forEach(row => {
                const rCode = (row.getAttribute('data-code') || '').toLowerCase();
                const rName = (row.getAttribute('data-name') || '').toLowerCase();
                const rRoom = row.getAttribute('data-room') || '';
                const rCond = row.getAttribute('data-condition') || '';

                const matchesQuery = !query || rCode.includes(query) || rName.includes(query);
                const matchesRoom = (room === 'all') || (rRoom === room);
                const matchesCond = (cond === 'all') || (rCond === cond);

                if (matchesQuery && matchesRoom && matchesCond) {
                    row.style.display = '';
                    total++;
                    if (rCond === 'Baik') baik++;
                    else if (rCond === 'Perbaikan') perbaikan++;
                    else if (rCond === 'Rusak') rusak++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('invTotalCount').innerText = `${total} Item`;
            document.getElementById('invBaikCount').innerText = `${baik} Item`;
            document.getElementById('invPerbaikanCount').innerText = `${perbaikan} Item`;
            document.getElementById('invRusakCount').innerText = `${rusak} Item`;
        }

        let editingAssetRow = null;

        function openAddAssetModal() {
            editingAssetRow = null;
            document.getElementById('assetModalTitle').innerText = 'Tambah Aset Baru';
            document.getElementById('assetForm').reset();
            document.getElementById('assetModal').classList.remove('hidden');
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function closeAssetModal() {
            document.getElementById('assetModal').classList.add('hidden');
        }

        function editAsset(code, name, room, qty, condition) {
            openAddAssetModal();
            editingAssetRow = event.target.closest('tr');
            document.getElementById('assetModalTitle').innerText = 'Edit Data Aset';
            document.getElementById('assetCode').value = code;
            document.getElementById('assetName').value = name;
            document.getElementById('assetRoom').value = room;
            document.getElementById('assetQuantity').value = qty;
            document.getElementById('assetCondition').value = condition;
        }

        function deleteAsset(btn, code) {
            if (confirm(`Apakah Anda yakin ingin menghapus data aset ${code}?`)) {
                const row = btn.closest('tr');
                row.remove();
                filterInventoryTable();
            }
        }

        function handleAssetSubmit(e) {
            e.preventDefault();
            const code = document.getElementById('assetCode').value.trim();
            const name = document.getElementById('assetName').value.trim();
            const room = document.getElementById('assetRoom').value;
            const qty = document.getElementById('assetQuantity').value;
            const cond = document.getElementById('assetCondition').value;

            let condBadge = '';
            if (cond === 'Baik') {
                condBadge = '<span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">Baik</span>';
            } else if (cond === 'Perbaikan') {
                condBadge = '<span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-800 border border-amber-200">Perbaikan</span>';
            } else {
                condBadge = '<span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-rose-50 text-rose-800 border border-rose-200">Rusak</span>';
            }

            if (editingAssetRow) {
                editingAssetRow.setAttribute('data-code', code.toLowerCase());
                editingAssetRow.setAttribute('data-name', name.toLowerCase());
                editingAssetRow.setAttribute('data-room', room);
                editingAssetRow.setAttribute('data-condition', cond);

                editingAssetRow.cells[0].innerText = code;
                editingAssetRow.cells[1].innerText = name;
                editingAssetRow.cells[2].innerText = room;
                editingAssetRow.cells[3].innerText = `${qty} Unit`;
                editingAssetRow.cells[4].innerHTML = condBadge;
            } else {
                const tbody = document.getElementById('inventoryTbody');
                const tr = document.createElement('tr');
                tr.className = 'inv-row hover:bg-[#F8FAFC]/80 transition';
                tr.setAttribute('data-code', code.toLowerCase());
                tr.setAttribute('data-name', name.toLowerCase());
                tr.setAttribute('data-room', room);
                tr.setAttribute('data-condition', cond);

                tr.innerHTML = `
                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">${code}</td>
                    <td class="py-3.5 px-4 font-semibold text-slate-900">${name}</td>
                    <td class="py-3.5 px-4 text-slate-600">${room}</td>
                    <td class="py-3.5 px-4 text-center font-mono font-medium">${qty} Unit</td>
                    <td class="py-3.5 px-4 text-center">${condBadge}</td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                        <button type="button" onclick="editAsset('${code}', '${name}', '${room}', ${qty}, '${cond}')" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition mr-1" title="Edit Aset"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                        <button type="button" onclick="deleteAsset(this, '${code}')" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition" title="Hapus Aset"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                    </td>
                `;
                tbody.prepend(tr);
            }

            closeAssetModal();
            filterInventoryTable();
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        // ----------------------------------------------------
        // 8. UTILITY ENGINE (SUB-TAB, PENGGUNA, PERAN)
        // ----------------------------------------------------
        function switchUtilitySubTab(subTab) {
            const btnPengguna = document.getElementById('tabBtnPengguna');
            const btnPeran = document.getElementById('tabBtnPeran');
            const contentPengguna = document.getElementById('subTabPenggunaContent');
            const contentPeran = document.getElementById('subTabPeranContent');

            if (subTab === 'pengguna') {
                btnPengguna.className = 'px-4 py-2.5 text-xs font-bold border-b-2 border-[#0F5132] text-[#0F5132] transition flex items-center gap-2 cursor-pointer';
                btnPeran.className = 'px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition flex items-center gap-2 cursor-pointer';
                contentPengguna.classList.remove('hidden');
                contentPeran.classList.add('hidden');
            } else {
                btnPeran.className = 'px-4 py-2.5 text-xs font-bold border-b-2 border-[#0F5132] text-[#0F5132] transition flex items-center gap-2 cursor-pointer';
                btnPengguna.className = 'px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition flex items-center gap-2 cursor-pointer';
                contentPeran.classList.remove('hidden');
                contentPengguna.classList.add('hidden');
            }

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function filterUserTable() {
            const query = (document.getElementById('userSearchInput').value || '').toLowerCase().trim();
            const filter = document.getElementById('userFilterSelect').value;
            const rows = Array.from(document.querySelectorAll('#usersTbody tr.user-row'));

            rows.forEach(row => {
                const name = (row.getAttribute('data-name') || '').toLowerCase();
                const email = (row.getAttribute('data-email') || '').toLowerCase();
                const approved = row.getAttribute('data-approved') === 'true';
                const locked = row.getAttribute('data-locked') === 'true';

                const matchesQuery = !query || name.includes(query) || email.includes(query);
                let matchesFilter = true;
                if (filter === 'approved') matchesFilter = approved;
                else if (filter === 'unapproved') matchesFilter = !approved;
                else if (filter === 'locked') matchesFilter = locked;

                if (matchesQuery && matchesFilter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function toggleSelectAllUsers(headerCheckbox) {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => {
                if (cb.closest('tr').style.display !== 'none') {
                    cb.checked = headerCheckbox.checked;
                }
            });
        }

        function toggleBulkActionDropdown() {
            document.getElementById('bulkActionsMenu').classList.toggle('hidden');
        }

        function executeBulkAction(action) {
            document.getElementById('bulkActionsMenu').classList.add('hidden');
            const selectedRows = Array.from(document.querySelectorAll('#usersTbody tr.user-row'))
                .filter(row => row.querySelector('.user-checkbox') && row.querySelector('.user-checkbox').checked);

            if (selectedRows.length === 0) {
                alert('Silakan pilih minimal 1 pengguna melalui checkbox di sebelah kiri.');
                return;
            }

            if (action === 'approve') {
                selectedRows.forEach(row => {
                    row.setAttribute('data-approved', 'true');
                    row.cells[2].querySelector('input[type="checkbox"]').checked = true;
                });
                alert(`${selectedRows.length} akun terpilih berhasil disetujui.`);
            } else if (action === 'lock') {
                selectedRows.forEach(row => {
                    row.setAttribute('data-locked', 'true');
                    row.cells[4].querySelector('input[type="checkbox"]').checked = true;
                });
                alert(`${selectedRows.length} akun terpilih berhasil dikunci out.`);
            } else if (action === 'unlock') {
                selectedRows.forEach(row => {
                    row.setAttribute('data-locked', 'false');
                    row.cells[4].querySelector('input[type="checkbox"]').checked = false;
                });
                alert(`Kunci akun pada ${selectedRows.length} pengguna berhasil dibuka.`);
            } else if (action === 'delete') {
                if (confirm(`Apakah Anda yakin ingin menghapus ${selectedRows.length} akun pengguna yang dipilih?`)) {
                    selectedRows.forEach(row => row.remove());
                }
            }
        }

        function toggleUserApproval(checkbox, userName) {
            const row = checkbox.closest('tr');
            row.setAttribute('data-approved', checkbox.checked ? 'true' : 'false');
        }

        function toggleUserLockout(checkbox, userName) {
            const row = checkbox.closest('tr');
            row.setAttribute('data-locked', checkbox.checked ? 'true' : 'false');
        }

        let editingUserRow = null;

        function openAddUserModal() {
            editingUserRow = null;
            document.getElementById('userModalTitle').innerText = 'Tambah Akun Pengguna';
            document.getElementById('userForm').reset();
            document.getElementById('userModal').classList.remove('hidden');
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function editUser(id, name, email, department) {
            openAddUserModal();
            editingUserRow = event.target.closest('tr');
            document.getElementById('userModalTitle').innerText = 'Edit Data Pengguna';
            document.getElementById('userFullName').value = name;
            document.getElementById('userEmail').value = email;
            document.getElementById('userDepartment').value = department;
        }

        function resetUserPassword(name, email) {
            const tempPass = 'Smkn4#' + Math.floor(1000 + Math.random() * 9000);
            alert(`Password untuk ${name} (${email}) berhasil di-reset!\n\nKata sandi sementara: ${tempPass}\n\nPengguna akan diminta mengganti sandi pada saat login berikutnya.`);
        }

        function deleteUser(btn, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus pengguna ${name}?`)) {
                const row = btn.closest('tr');
                row.remove();
            }
        }

        function handleUserSubmit(e) {
            e.preventDefault();
            const name = document.getElementById('userFullName').value.trim();
            const email = document.getElementById('userEmail').value.trim();
            const role = document.getElementById('userRole').value;
            const dept = document.getElementById('userDepartment').value.trim();
            const isApproved = document.getElementById('userIsApproved').checked;

            const now = new Date();
            const dateStr = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' +
                            now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');

            const roleBadge = role === 'Master Admin' ? 
                '<span class="text-[9px] font-bold uppercase px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800">Master</span>' : '';

            if (editingUserRow) {
                editingUserRow.setAttribute('data-name', name.toLowerCase());
                editingUserRow.setAttribute('data-email', email.toLowerCase());
                editingUserRow.setAttribute('data-role', role);
                editingUserRow.setAttribute('data-approved', isApproved ? 'true' : 'false');

                editingUserRow.cells[1].innerHTML = `<div class="font-bold text-slate-900 flex items-center gap-1.5"><span>${name}</span>${roleBadge}</div>`;
                editingUserRow.cells[2].querySelector('input').checked = isApproved;
                editingUserRow.cells[3].innerText = email;
                editingUserRow.cells[7].innerText = dept;
            } else {
                const tbody = document.getElementById('usersTbody');
                const tr = document.createElement('tr');
                tr.className = 'user-row hover:bg-[#F8FAFC]/80 transition';
                tr.setAttribute('data-name', name.toLowerCase());
                tr.setAttribute('data-email', email.toLowerCase());
                tr.setAttribute('data-role', role);
                tr.setAttribute('data-approved', isApproved ? 'true' : 'false');
                tr.setAttribute('data-locked', 'false');

                tr.innerHTML = `
                    <td class="py-3.5 px-3 text-center">
                        <input type="checkbox" class="user-checkbox w-3.5 h-3.5 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                    </td>
                    <td class="py-3.5 px-3">
                        <div class="font-bold text-slate-900 flex items-center gap-1.5">
                            <span>${name}</span>
                            ${roleBadge}
                        </div>
                    </td>
                    <td class="py-3.5 px-3 text-center">
                        <input type="checkbox" ${isApproved ? 'checked' : ''} onchange="toggleUserApproval(this, '${name}')" class="w-4 h-4 rounded text-[#0F5132] accent-[#0F5132] cursor-pointer">
                    </td>
                    <td class="py-3.5 px-3 font-mono text-[11px] text-slate-600">${email}</td>
                    <td class="py-3.5 px-3 text-center">
                        <input type="checkbox" onchange="toggleUserLockout(this, '${name}')" class="w-4 h-4 rounded text-rose-600 accent-rose-600 cursor-pointer">
                    </td>
                    <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-500">${dateStr}</td>
                    <td class="py-3.5 px-3 whitespace-nowrap font-mono text-[11px] text-slate-400 italic">Belum Pernah</td>
                    <td class="py-3.5 px-3 text-slate-600">${dept}</td>
                    <td class="py-3.5 px-3 text-right whitespace-nowrap">
                        <button type="button" onclick="editUser(99, '${name}', '${email}', '${dept}')" class="p-1.5 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition mr-0.5"><i data-lucide="pencil" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                        <button type="button" onclick="resetUserPassword('${name}', '${email}')" class="p-1.5 text-slate-400 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition mr-0.5"><i data-lucide="key" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                        <button type="button" onclick="deleteUser(this, '${name}')" class="p-1.5 text-slate-400 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition"><i data-lucide="trash-2" class="w-3.5 h-3.5 stroke-[1.75]"></i></button>
                    </td>
                `;
                tbody.prepend(tr);
            }

            closeUserModal();
            filterUserTable();
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        // ----------------------------------------------------
        // 9. EXPORT DATA (PDF & EXCEL)
        // ----------------------------------------------------
        function exportToPDF() {
            toggleExportDropdown();
            window.print();
        }

        function exportToExcel() {
            toggleExportDropdown();
            const table = document.getElementById('ticketsTable');
            let csv = '\uFEFF';
            csv += '"Kode Tiket","Waktu Lapor","Ruangan","Barang","Judul Keluhan","Pelapor","Dukungan","Urgensi","Status"\r\n';

            const rows = table.querySelectorAll('tbody tr.ticket-row');
            rows.forEach(r => {
                if (r.style.display !== 'none') {
                    const code = r.getAttribute('data-code');
                    const room = r.getAttribute('data-room');
                    const asset = r.getAttribute('data-asset');
                    const title = (r.getAttribute('data-title') || '').replace(/"/g, '""');
                    const reporter = r.getAttribute('data-reporter');
                    const upvotes = r.getAttribute('data-upvotes');
                    const priority = r.getAttribute('data-priority');
                    const status = r.getAttribute('data-status');

                    csv += `"${code}","${r.cells[0].innerText.split('\n')[1] || ''}","${room}","${asset}","${title}","${reporter}","+${upvotes} Pelapor","${priority}","${status}"\r\n`;
                }
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `rekap_sarpras_smkn4_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Global Click Listener for Dropdowns
        window.addEventListener('click', function (e) {
            const btnExp = document.getElementById('btnExport');
            const menuExp = document.getElementById('exportDropdownMenu');
            if (btnExp && menuExp && !btnExp.contains(e.target) && !menuExp.contains(e.target)) {
                menuExp.classList.add('hidden');
            }

            const btnDate = document.getElementById('btnFilterDate');
            const menuDate = document.getElementById('dateFilterDropdown');
            if (btnDate && menuDate && !btnDate.contains(e.target) && !menuDate.contains(e.target)) {
                menuDate.classList.add('hidden');
            }

            const btnRoom = document.getElementById('btnFilterRoom');
            const menuRoom = document.getElementById('roomFilterDropdown');
            if (btnRoom && menuRoom && !btnRoom.contains(e.target) && !menuRoom.contains(e.target)) {
                menuRoom.classList.add('hidden');
            }

            const btnPri = document.getElementById('btnFilterPriority');
            const menuPri = document.getElementById('priorityFilterDropdown');
            if (btnPri && menuPri && !btnPri.contains(e.target) && !menuPri.contains(e.target)) {
                menuPri.classList.add('hidden');
            }

            const btnBulk = document.getElementById('btnBulkActions');
            const menuBulk = document.getElementById('bulkActionsMenu');
            if (btnBulk && menuBulk && !btnBulk.contains(e.target) && !menuBulk.contains(e.target)) {
                menuBulk.classList.add('hidden');
            }
        });

        // Close Modals on Escape Key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeDetailDrawer();
                closeQrModal();
                closeAssetModal();
                closeUserModal();
            }
        });
    </script>
</body>
</html>
