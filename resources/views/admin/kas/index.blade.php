<x-app-layout>
    <div class="max-w-6xl mx-auto py-12">
        @if(session('success')) <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div> @endif
        
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-6 rounded shadow border-l-4 border-blue-500">
                <h3 class="text-gray-500 text-sm">TOTAL SALDO</h3>
                <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($saldo,0,',','.') }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow border-l-4 border-green-500">
                <h3 class="text-gray-500 text-sm">PEMASUKAN</h3>
                <p class="text-2xl font-bold text-green-600">Rp{{ number_format($pemasukan,0,',','.') }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow border-l-4 border-red-500">
                <h3 class="text-gray-500 text-sm">PENGELUARAN</h3>
                <p class="text-2xl font-bold text-red-600">Rp{{ number_format($pengeluaran,0,',','.') }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Riwayat Transaksi</h2>
                <a href="{{ route('admin.pembayaran-cash.create') }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">+ Input Cash</a>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="p-3 text-sm">Tanggal</th>
                        <th class="p-3 text-sm">Keterangan</th>
                        <th class="p-3 text-sm">Jenis</th>
                        <th class="p-3 text-sm">Nominal</th>
                        <th class="p-3 text-sm">Pencatat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $t)
                    <tr class="border-b">
                        <td class="p-3 text-sm">{{ $t->tanggal_transaksi }}</td>
                        <td class="p-3 text-sm">{{ $t->keterangan }}</td>
                        <td class="p-3 text-sm">
                            <span class="{{ $t->jenis == 'pemasukan' ? 'text-green-600' : 'text-red-600' }} font-bold uppercase text-xs">{{ $t->jenis }}</span>
                        </td>
                        <td class="p-3 text-sm font-medium">Rp{{ number_format($t->nominal,0,',','.') }}</td>
                        <td class="p-3 text-sm">{{ $t->admin->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>