<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sarpras - Selesaikan Perbaikan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-xl font-bold mb-1">Penyelesaian Perbaikan Tiket #{{ $ticketId }}</h1>
        <p class="text-sm text-slate-500 mb-4">Dashboard Admin / Teknisi Sarpras</p>

        <form action="{{ route('sarpras.complete', $ticketId) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium">Biaya Perbaikan (Rp)</label>
                <input type="number" name="repair_cost" value="150000" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Catatan Tindakan Perbaikan</label>
                <textarea name="note" class="w-full border rounded p-2" required>Pembersihan saluran pembuangan AC dan pengisian freon.</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium">Foto Sesudah Diperbaiki</label>
                <input type="file" name="photo_after" accept="image/*" class="w-full border rounded p-2" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700">
                Simpan & Selesaikan Tiket
            </button>
        </form>
    </div>
</body>
</html>