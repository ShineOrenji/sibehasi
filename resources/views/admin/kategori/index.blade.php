<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900"><i class="fa-solid fa-tags mr-2 text-blue-600"></i>Master Kategori</h1>
                <p class="text-sm text-gray-500">Kelola kategori untuk Pemasukan (Tagihan) dan Pengeluaran.</p>
            </div>

            @if(session('success')) <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg font-medium border border-emerald-100">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="mb-4 bg-rose-50 text-rose-700 p-4 rounded-lg font-medium border border-rose-100">{{ session('error') }}</div> @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Form Tambah -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-fit">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Tambah Kategori</h3>
                    <form action="{{ route('admin.kategori.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kategori</label>
                            <select name="jenis" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                                <option value="pemasukan">Pemasukan (Untuk Tagihan/Iuran)</option>
                                <option value="pengeluaran">Pengeluaran (Untuk Belanja/Kas)</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kategori</label>
                            <input type="text" name="nama_kategori" placeholder="Contoh: Konsumsi Acara" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition">Simpan Kategori</button>
                    </form>
                </div>

                <!-- Tabel Data -->
                <div class="md:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Daftar Kategori</h3>
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                <th class="p-3 font-semibold rounded-tl-lg">Nama Kategori</th>
                                <th class="p-3 font-semibold">Jenis</th>
                                <th class="p-3 font-semibold text-right rounded-tr-lg">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($kategori as $k)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 text-sm font-bold text-gray-800">{{ $k->nama_kategori }}</td>
                                <td class="p-3 text-sm">
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-full {{ $k->jenis == 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ strtoupper($k->jenis) }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs bg-rose-50 text-rose-600 hover:bg-rose-100 px-2 py-1.5 rounded font-semibold transition"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>