<x-app-layout>
    <div class="pt-8 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 pb-12" x-data="{ modalOpen: false, imageSrc: '' }">
        
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900"><i class="fa-solid fa-bell mr-2 text-rose-600"></i>Konfirmasi Pembayaran</h1>
            <p class="text-sm text-gray-500">Cek bukti transfer mahasiswa. Hati-hati bukti palsu!</p>
        </div>

        @if(session('success')) <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg font-bold border border-emerald-100">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="mb-4 bg-rose-50 text-rose-700 p-4 rounded-lg font-bold border border-rose-100">{{ session('error') }}</div> @endif

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                            <th class="p-4 font-semibold">Tgl Bayar</th>
                            <th class="p-4 font-semibold">Mahasiswa</th>
                            <th class="p-4 font-semibold">Tagihan</th>
                            <th class="p-4 font-semibold text-center">Bukti Transfer</th>
                            <th class="p-4 font-semibold text-right">Keputusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pending as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y, H:i') }}</td>
                            <td class="p-4">
                                <p class="text-sm font-bold text-gray-900">{{ $p->anggota->user->name }}</p>
                                <p class="text-xs text-gray-500">NIM: {{ $p->anggota->nim }}</p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-bold text-blue-600">{{ $p->periode->nama_periode }}</p>
                                <p class="text-xs font-semibold text-gray-700">Rp{{ number_format($p->periode->nominal, 0, ',', '.') }}</p>
                            </td>
                            <td class="p-4 text-center">
                                <!-- Tombol Lihat Foto -->
                                <button @click="modalOpen = true; imageSrc = '{{ asset('uploads/bukti/' . $p->bukti_bayar) }}'" class="text-xs bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-3 py-2 rounded-lg font-bold transition border border-indigo-100">
                                    <i class="fa-solid fa-image mr-1"></i> Lihat Foto
                                </button>
                            </td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <!-- Tombol ACC -->
                                    <form action="{{ route('admin.konfirmasi.acc', $p->id) }}" method="POST" onsubmit="return confirm('Yakin bukti valid dan uang sudah masuk? (Otomatis masuk Buku Kas)');">
                                        @csrf
                                        <button type="submit" class="text-xs bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-2 rounded-lg font-bold transition shadow-sm">
                                            <i class="fa-solid fa-check"></i> ACC
                                        </button>
                                    </form>
                                    
                                    <!-- Tombol Tolak -->
                                    <form action="{{ route('admin.konfirmasi.tolak', $p->id) }}" method="POST" onsubmit="return confirm('Tolak dan hapus bukti transfer ini? (Mahasiswa disuruh upload ulang)');">
                                        @csrf
                                        <button type="submit" class="text-xs bg-rose-500 hover:bg-rose-600 text-white px-3 py-2 rounded-lg font-bold transition shadow-sm">
                                            <i class="fa-solid fa-xmark"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500 font-medium">Belum ada pembayaran yang perlu dikonfirmasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL LIHAT FOTO BUKTI BESAR -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" x-transition style="display: none;">
            <div @click.away="modalOpen = false" class="relative max-w-3xl w-full">
                <!-- Tombol Close (Silang) -->
                <button @click="modalOpen = false" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                    <i class="fa-solid fa-xmark text-3xl"></i>
                </button>
                <!-- Gambar -->
                <img :src="imageSrc" alt="Bukti Transfer" class="w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">
            </div>
        </div>

    </div>
</x-app-layout>