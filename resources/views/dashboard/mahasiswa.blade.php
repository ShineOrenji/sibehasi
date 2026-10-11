<x-app-layout>
    <div class="pt-8 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 pb-12" x-data="{ modalOpen: false, selectedPeriode: null, selectedNama: '', selectedNominal: 0 }">
        
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900"><i class="fa-solid fa-hand-wave mr-2 text-blue-600"></i>Halo, {{ Auth::user()->name }}!</h1>
            <p class="text-sm text-gray-500">NIM: {{ Auth::user()->anggota->nim ?? '-' }} | Angkatan: {{ Auth::user()->anggota->angkatan ?? '-' }}</p>
        </div>

        @if(session('success')) <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg font-bold border border-emerald-100">{{ session('success') }}</div> @endif

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold mb-4 border-b pb-2">Tagihan Aktif Saya</h3>
            
            <div class="space-y-4">
                @forelse(\App\Models\PeriodeIuran::where('status', 'aktif')->get() as $periode)
                    @php
                        // Cek apakah mahasiswa ini sudah bayar tagihan ini
                        $cekBayar = \App\Models\PembayaranIuran::where('periode_id', $periode->id)
                                    ->where('anggota_id', Auth::user()->anggota->id)
                                    ->first();
                    @endphp

                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-gray-100 transition">
                        <div>
                            <p class="font-bold text-gray-900">{{ $periode->nama_periode }}</p>
                            <p class="text-sm text-gray-500">Batas: {{ \Carbon\Carbon::parse($periode->deadline_pembayaran)->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-right">
                            
                            <!-- TAMPILKAN NOMINAL ATAU DENDA OTOMATIS -->
                            <div>
                                @if($periode->nominal_akhir > $periode->nominal)
                                    <span class="block text-[10px] text-rose-500 font-extrabold uppercase">Terlambat (+Denda)</span>
                                    <span class="font-extrabold text-rose-600 text-lg">Rp{{ number_format($periode->nominal_akhir, 0, ',', '.') }}</span>
                                @else
                                    <span class="font-extrabold text-gray-900 text-lg">Rp{{ number_format($periode->nominal, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            
                            @if(!$cekBayar)
                                <!-- Belum Bayar -> Buka Modal QRIS (Menggunakan nominal_akhir) -->
                                <button @click="modalOpen = true; selectedPeriode = {{ $periode->id }}; selectedNama = '{{ $periode->nama_periode }}'; selectedNominal = '{{ number_format($periode->nominal_akhir, 0, ',', '.') }}'" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition shadow-sm shadow-blue-500/30">
                                    <i class="fa-solid fa-qrcode mr-1"></i> Bayar
                                </button>
                            @elseif($cekBayar->status == 'pending')
                                <!-- Sedang Diperiksa Admin -->
                                <span class="bg-amber-100 text-amber-700 font-bold py-2 px-4 rounded-lg text-sm cursor-not-allowed">
                                    <i class="fa-solid fa-hourglass-half mr-1"></i> Menunggu Konfirmasi
                                </span>
                            @else
                                <!-- Sudah Lunas -->
                                <span class="bg-emerald-100 text-emerald-700 font-bold py-2 px-4 rounded-lg text-sm">
                                    <i class="fa-solid fa-circle-check mr-1"></i> LUNAS
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4 font-medium">Hore! Tidak ada tagihan aktif saat ini.</p>
                @endforelse
            </div>
        </div>

        <!-- MODAL QRIS & UPLOAD BUKTI (Disembunyikan, muncul saat klik Bayar) -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4" x-transition style="display: none;">
            <div @click.away="modalOpen = false" class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden relative">
                <!-- Tombol Close (Silang) -->
                <button @click="modalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>

                <div class="p-6 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Pembayaran Tagihan</h3>
                    <p class="text-sm text-gray-500 font-semibold" x-text="selectedNama"></p>
                    <p class="text-2xl font-extrabold text-blue-600 mt-2 mb-4">Rp<span x-text="selectedNominal"></span></p>
                    
                    <!-- Area Gambar QRIS -->
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 inline-block mb-6">
                        <p class="text-xs font-bold text-blue-800 mb-2 uppercase">Scan QRIS Dana Bisnis HIMASI</p>
                        <!-- GANTI NAMA FILE QRIS DI BAWAH INI SESUAI FILE ANDA -->
                        <img src="{{ asset('assets/images/qris/qris.jpeg') }}" alt="QRIS HIMASI" class="w-48 h-48 object-cover rounded-lg mx-auto shadow-sm">
                    </div>

                    <!-- Form Upload Bukti -->
                    <form action="{{ route('mahasiswa.pay') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="periode_id" :value="selectedPeriode">
                        
                        <div class="text-left mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Transfer (Wajib)</label>
                            <input type="file" name="bukti_bayar" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg" required>
                            <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG (Max: 2MB).</p>
                        </div>
                        
                        <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3 px-4 rounded-xl transition">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>