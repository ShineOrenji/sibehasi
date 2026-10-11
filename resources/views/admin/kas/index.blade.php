<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @if(session('success')) 
            <div class="bg-emerald-50 text-emerald-700 font-bold p-4 rounded-xl mb-6 shadow-sm border border-emerald-100">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div> 
        @endif
        
        <!-- Statistik Kas (Responsif HP) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-b-4 border-b-blue-500 hover:shadow-md transition">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">TOTAL SALDO</h3>
                <p class="text-3xl font-extrabold text-gray-900">Rp{{ number_format($saldo,0,',','.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-b-4 border-b-emerald-500 hover:shadow-md transition">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">PEMASUKAN</h3>
                <p class="text-3xl font-extrabold text-emerald-600">Rp{{ number_format($pemasukan,0,',','.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-b-4 border-b-rose-500 hover:shadow-md transition">
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">PENGELUARAN</h3>
                <p class="text-3xl font-extrabold text-rose-600">Rp{{ number_format($pengeluaran,0,',','.') }}</p>
            </div>
        </div>

        <!-- Tabel Riwayat Kas -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-6 border-b border-gray-100 gap-4">
                <h2 class="text-xl font-extrabold text-gray-900">Riwayat Transaksi</h2>
                <div class="flex gap-2 w-full sm:w-auto">
                    <form action="{{ route('admin.kas.destroyAll') }}" method="POST" id="hapus-semua-kas">
                        @csrf
                        <button type="button" onclick="konfirmasiHapus('hapus-semua-kas', 'Kosongkan tabel Kas? Laporan Excel tetap aman.')" class="bg-rose-100 text-rose-600 font-bold px-4 py-2.5 rounded-xl text-sm hover:bg-rose-600 hover:text-white transition shadow-sm w-full sm:w-auto text-center"><i class="fa-solid fa-trash mr-1"></i> Kosongkan</button>
                    </form>
                    <a href="{{ route('admin.pembayaran-cash.create') }}" class="bg-blue-600 text-white font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-blue-700 transition shadow-sm w-full sm:w-auto text-center"><i class="fa-solid fa-plus mr-1"></i> Input Cash</a>
                </div>
            </div>
            
            <!-- Table Wrapper Biar Bisa Digeser ke Kanan di HP -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pencatat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($transaksi as $t)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d M Y') }}</td>
                            <td class="p-4 text-sm text-gray-900 font-medium whitespace-normal min-w-[200px]">{{ $t->keterangan }}</td>
                            <td class="p-4 text-sm">
                                <span class="{{ $t->jenis == 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} font-bold text-xs px-3 py-1 rounded-full uppercase">
                                    {{ $t->jenis }}
                                </span>
                            </td>
                            <td class="p-4 text-sm font-extrabold text-gray-900">Rp{{ number_format($t->nominal,0,',','.') }}</td>
                            <td class="p-4 text-xs font-bold text-gray-500">{{ $t->admin->name ?? '-' }}</td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.kas.destroy', $t->id) }}" method="POST" id="hapus-kas-{{ $t->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="konfirmasiHapus('hapus-kas-{{ $t->id }}')" class="bg-rose-100 text-rose-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-rose-600 hover:text-white transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>