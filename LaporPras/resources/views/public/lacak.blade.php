<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LaporPras - Tiket Terkirim</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow text-center">
        <div class="inline-block bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-semibold mb-2">
            Output Stored Procedure (sp_buat_laporan_rusak)
        </div>
        <h2 class="text-2xl font-bold mb-2">Kode Tiket: {{ $code }}</h2>
        @if(session('success'))
            <p class="text-slate-700 bg-slate-50 border p-3 rounded-lg mb-4">{{ session('success') }}</p>
        @endif
        <a href="{{ route('public.form', 'smkn-4-bandung') }}" class="text-emerald-600 underline text-sm">Buat Laporan Lain</a>
    </div>
</body>
</html>