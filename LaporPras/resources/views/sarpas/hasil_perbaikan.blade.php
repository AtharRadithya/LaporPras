<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sarpras - Rekap Perbaikan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow text-center">
        <div class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold mb-2">
            Output Stored Procedure (sp_selesaikan_perbaikan)
        </div>
        <p class="text-slate-700 bg-slate-50 border p-3 rounded-lg my-3">{{ $statusPesan }}</p>
        <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-lg">
            <p class="text-sm text-emerald-700">Total Akumulasi Biaya Perbaikan Sekolah (sf_total_biaya_perbaikan):</p>
            <p class="text-2xl font-bold text-emerald-900">Rp {{ $totalBiaya }}</p>
        </div>
    </div>
</body>
</html>