<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LaporPras - Form Laporan Kerusakan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow">
        <h1 class="text-xl font-bold mb-1">Lapor Kerusakan Fasilitas</h1>
        <p class="text-sm text-slate-500 mb-4">Sekolah: <strong>{{ $slug }}</strong></p>

        <form action="{{ route('public.store', $slug) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium">Nama Pelapor</label>
                <input type="text" name="reporter_name" value="Kennan - XII RPL 1" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Status / Jabatan</label>
                <input type="text" name="reporter_info" value="Siswa" class="w-full border rounded p-2" required>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-sm font-medium">ID Ruangan</label>
                    <input type="number" name="location_id" value="1" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">ID Aset (Opsional)</label>
                    <input type="number" name="asset_id" value="1" class="w-full border rounded p-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Judul Kerusakan</label>
                <input type="text" name="title" value="AC Lab RPL 1 Bocor" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Deskripsi Kerusakan</label>
                <textarea name="description" class="w-full border rounded p-2" required>Air menetes dari unit indoor AC sebelah papan tulis.</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium">Prioritas</label>
                <select name="priority" class="w-full border rounded p-2">
                    <option value="Rendah">Rendah</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Tinggi" selected>Tinggi</option>
                    <option value="Darurat">Darurat</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Foto Bukti Kerusakan</label>
                <input type="file" name="photo_before" accept="image/*" class="w-full border rounded p-2" required>
            </div>
            <button type="submit" class="w-full bg-emerald-600 text-white font-semibold py-2 rounded-lg hover:bg-emerald-700">
                Kirim Laporan Kerusakan
            </button>
        </form>
    </div>
</body>
</html>