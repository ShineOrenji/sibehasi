<x-app-layout>
    <div class="max-w-3xl mx-auto py-12">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Catat Pengeluaran Kas</h2>
            
            <form action="{{ route('admin.pengeluaran.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Kategori</label>
                    <select name="kategori_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Nominal (Rp)</label>
                    <input type="number" name="nominal" placeholder="Contoh: 150000" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1 text-gray-700">Keterangan / Deskripsi</label>
                    <textarea name="keterangan" rows="3" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Misal: Beli snack rapat evaluasi" required></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="bg-rose-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-rose-700">Simpan Pengeluaran</button>
                    <a href="{{ route('admin.kas.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-300">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>