<x-app-layout>
    <div class="pt-8 px-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Kelola Tagihan & Acara</h1>
                <p class="text-sm text-gray-500">Buat tagihan Uang Kas, MABIM, PDH, atau acara lainnya.</p>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg font-medium border border-emerald-100">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-rose-50 text-rose-700 p-4 rounded-lg font-medium border border-rose-100">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Tambah -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-fit">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Buat Tagihan Baru</h3>
                    <form action="{{ route('admin.periode.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori Tagihan</label>
                            <select name="kategori_id" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Tagihan / Acara</label>
                            <input type="text" name="nama_periode" placeholder="Contoh: MABIM 2026 / Kas Nov" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nominal (Rp)</label>
                            <input type="number" name="nominal" placeholder="Contoh: 50000" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Tgl Mulai</label>
                                <input type="date" name="tanggal_mulai" class="w-full text-sm border-gray-300 rounded-lg shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Deadline</label>
                                <input type="date" name="deadline_pembayaran" class="w-full text-sm border-gray-300 rounded-lg shadow-sm" required>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-2.5 rounded-lg transition">Simpan Tagihan</button>
                    </form>
                </div>

                <!-- Tabel Data -->
                <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Daftar Tagihan Berjalan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                                    <th class="p-3 font-semibold rounded-tl-lg">Nama Tagihan</th>
                                    <th class="p-3 font-semibold">Nominal</th>
                                    <th class="p-3 font-semibold">Durasi</th>
                                    <th class="p-3 font-semibold text-center">Status</th>
                                    <th class="p-3 font-semibold text-right rounded-tr-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($periode as $p)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3">
                                        <p class="text-sm font-bold text-gray-900">{{ $p->nama_periode }}</p>
                                        <p class="text-xs text-blue-600 font-semibold">{{ $p->kategori->nama_kategori ?? 'Umum' }}</p>
                                    </td>
                                    <td class="p-3 text-sm font-semibold text-gray-700">Rp{{ number_format($p->nominal, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        <p class="text-xs text-gray-500">Mulai: {{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M y') : '-' }}</p>
                                        <p class="text-xs text-rose-500 font-medium">Batas: {{ \Carbon\Carbon::parse($p->deadline_pembayaran)->format('d M y') }}</p>
                                    </td>
                                    <td class="p-3 text-center">
                                        <span class="inline-block px-2 py-1 text-[10px] font-bold rounded-full {{ $p->status == 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600' }}">
                                            {{ strtoupper($p->status) }}
                                        </span>
                                        <p class="text-[11px] text-gray-500 mt-1">{{ $p->lunas_count }} Lunas</p>
                                    </td>
                                    <td class="p-3 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- Tombol Lihat Pembayar -->
                                            <a href="{{ route('admin.periode.show', $p->id) }}" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-2 py-1.5 rounded font-semibold transition">Lihat Data</a>
                                            
                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('admin.periode.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tagihan ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs bg-rose-50 text-rose-600 hover:bg-rose-100 px-2 py-1.5 rounded font-semibold transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>