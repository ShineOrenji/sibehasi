<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Keuangan</h1>
                    <p class="text-sm text-gray-500">Rekapitulasi arus kas HIMASI per bulan.</p>
                </div>
                
                <!-- Form Filter -->
                <form action="{{ route('admin.laporan.index') }}" method="GET" class="mt-4 md:mt-0 flex space-x-2">
                    <select name="bulan" class="border-gray-300 rounded-lg shadow-sm text-sm">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                    <select name="tahun" class="border-gray-300 rounded-lg shadow-sm text-sm">
                        @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-black transition">Tampilkan</button>
                </form>
            </div>

            <!-- Kartu Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm border-l-4 border-l-blue-500">
                    <p class="text-xs font-bold text-gray-500 uppercase">Total Pemasukan</p>
                    <h3 class="text-2xl font-bold text-emerald-600 mt-1">Rp{{ number_format($pemasukan, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm border-l-4 border-l-rose-500">
                    <p class="text-xs font-bold text-gray-500 uppercase">Total Pengeluaran</p>
                    <h3 class="text-2xl font-bold text-rose-600 mt-1">Rp{{ number_format($pengeluaran, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm border-l-4 border-l-gray-800">
                    <p class="text-xs font-bold text-gray-500 uppercase">Saldo Bersih Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">Rp{{ number_format($saldo, 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Tabel Detail -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-700">Rincian Transaksi</h3>
                    <a href="{{ route('admin.laporan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center transition">
                        📄 Export PDF
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white border-b border-gray-100 text-xs uppercase text-gray-500">
                                <th class="p-4 font-semibold">Tanggal</th>
                                <th class="p-4 font-semibold">Keterangan</th>
                                <th class="p-4 font-semibold">Kategori</th>
                                <th class="p-4 font-semibold text-right">Pemasukan</th>
                                <th class="p-4 font-semibold text-right">Pengeluaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transaksi as $t)
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d/m/Y') }}</td>
                                <td class="p-4 text-sm font-medium text-gray-800">{{ $t->keterangan }}</td>
                                <td class="p-4 text-sm text-gray-500">{{ $t->kategori->nama_kategori ?? '-' }}</td>
                                <td class="p-4 text-sm font-bold text-emerald-600 text-right">
                                    {{ $t->jenis == 'pemasukan' ? 'Rp' . number_format($t->nominal, 0, ',', '.') : '-' }}
                                </td>
                                <td class="p-4 text-sm font-bold text-rose-600 text-right">
                                    {{ $t->jenis == 'pengeluaran' ? 'Rp' . number_format($t->nominal, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">Tidak ada transaksi pada bulan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>