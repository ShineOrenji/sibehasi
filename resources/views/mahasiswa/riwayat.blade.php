<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-extrabold text-gray-900"><i class="fa-solid fa-clock-rotate-left text-blue-600 mr-2"></i>Riwayat Pembayaran</h2>
            <form action="{{ route('mahasiswa.riwayat.destroyAll') }}" method="POST" id="hapus-semua-riwayat">
                @csrf
                <button type="button" onclick="konfirmasiHapus('hapus-semua-riwayat', 'Bersihkan semua riwayat?')" class="bg-rose-600 text-white font-bold px-4 py-2 rounded-xl text-sm shadow-sm hover:bg-rose-700">Bersihkan Riwayat</button>
            </form>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase">Tagihan</th>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(\App\Models\PembayaranIuran::where('anggota_id', Auth::user()->anggota->id)->where('is_hidden_mhs', false)->latest()->get() as $riwayat)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 text-sm font-bold text-gray-900">{{ $riwayat->periode->nama_periode }}</td>
                        <td class="p-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($riwayat->tanggal_bayar)->format('d M Y') }}</td>
                        <td class="p-4 text-sm">
                            @if($riwayat->status == 'sukses') <span class="bg-emerald-100 text-emerald-700 font-bold px-3 py-1 rounded-full text-xs">LUNAS</span>
                            @elseif($riwayat->status == 'pending') <span class="bg-amber-100 text-amber-700 font-bold px-3 py-1 rounded-full text-xs">PENDING</span>
                            @else <span class="bg-rose-100 text-rose-700 font-bold px-3 py-1 rounded-full text-xs">DITOLAK</span> @endif
                        </td>
                        <td class="p-4 text-center">
                            <form action="{{ route('mahasiswa.riwayat.destroy', $riwayat->id) }}" method="POST" id="hapus-riwayat-{{ $riwayat->id }}">
                                @csrf @method('DELETE')
                                <button type="button" onclick="konfirmasiHapus('hapus-riwayat-{{ $riwayat->id }}', 'Sembunyikan riwayat ini?')" class="text-rose-500 hover:text-rose-700 font-bold text-xs"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-8 text-center text-gray-500">Belum ada riwayat transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>