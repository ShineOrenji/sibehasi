<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Data Pembayar: {{ $periode->nama_periode }}</h1>
                    <p class="text-sm text-gray-500">Daftar mahasiswa yang sudah LUNAS pada tagihan ini.</p>
                </div>
                <a href="{{ route('admin.periode.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg text-sm">Kembali</a>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="p-4 text-sm font-semibold text-gray-600">No</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">Nama Mahasiswa</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">NIM</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">Metode</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">Waktu Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pembayaran as $index => $p)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="p-4 text-sm font-bold text-gray-900">{{ $p->anggota->user->name }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $p->anggota->nim }}</td>
                            <td class="p-4 text-sm">
                                <span class="px-2 py-1 text-xs font-bold rounded {{ $p->metode_pembayaran == 'online' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ strtoupper($p->metode_pembayaran) }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y, H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">Belum ada mahasiswa yang membayar tagihan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>