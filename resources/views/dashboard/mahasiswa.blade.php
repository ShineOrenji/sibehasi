<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Halo, {{ Auth::user()->name }} 👋</h1>
                <!-- PANEL NOTIFIKASI -->
                <div class="mb-8 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            🔔 Notifikasi 
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="ml-2 bg-rose-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ Auth::user()->unreadNotifications->count() }} Baru</span>
                            @endif
                        </h3>
                    </div>

                    <div class="space-y-3">
                        @forelse(Auth::user()->notifications as $notif)
                            <div class="p-4 rounded-lg flex justify-between items-start {{ $notif->read_at ? 'bg-gray-50' : 'bg-blue-50 border border-blue-100' }}">
                                <div>
                                    <h4 class="text-sm font-bold {{ $notif->read_at ? 'text-gray-700' : 'text-blue-700' }}">{{ $notif->data['judul'] }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notif->data['pesan'] }}</p>
                                    <span class="text-xs text-gray-400 mt-2 block">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    @if(!$notif->read_at)
                                        <form action="{{ route('notifikasi.read', $notif->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-blue-600 hover:underline">Tandai Dibaca</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifikasi.destroy', $notif->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-500 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Tidak ada notifikasi saat ini.</p>
                        @endforelse
                    </div>
                </div>
                <p class="text-sm text-gray-500">Selamat datang di portal keuangan HIMASI.</p>
            </div>

            @if($periodeAktif)
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-8 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Tagihan Aktif</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $periodeAktif->nama_periode }}</p>
                    <p class="text-lg text-gray-600">Rp{{ number_format($periodeAktif->nominal, 0, ',', '.') }}</p>
                </div>
                <div>
                    @if($statusIuran && $statusIuran->status === 'sukses')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-emerald-100 text-emerald-700">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            LUNAS
                        </span>
                    @else
                        <!-- Tombol ini nanti akan dihubungkan ke Midtrans di Phase 5 -->
                        <button id="pay-button" onclick="payIuran({{ $periodeAktif->id }})" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg font-semibold shadow-sm transition">
                            Bayar Sekarang
                        </button>
                    @endif
                </div>
            </div>
            @endif

            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Riwayat Pembayaran Terbaru</h3>
                </div>
                
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-3 text-sm font-medium text-gray-500">Periode</th>
                            <th class="pb-3 text-sm font-medium text-gray-500">Nominal</th>
                            <th class="pb-3 text-sm font-medium text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($riwayat as $r)
                        <tr>
                            <td class="py-4 text-sm font-medium text-gray-900">{{ $r->periode->nama_periode }}</td>
                            <td class="py-4 text-sm text-gray-600">Rp{{ number_format($r->nominal_bayar, 0, ',', '.') }}</td>
                            <td class="py-4">
                                @if($r->status == 'sukses')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold">LUNAS</span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-50 text-yellow-600 rounded-full text-xs font-bold uppercase">{{ $r->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-4 text-sm text-gray-500 text-center">Belum ada riwayat pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Script Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        function payIuran(periodeId) {
            const btn = document.getElementById('pay-button');
            btn.innerHTML = 'Memproses...';
            btn.disabled = true;

            fetch("{{ route('mahasiswa.pay') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ periode_id: periodeId })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || "Gagal menghubungi Midtrans");
                }
                return data;
            })
            .then(data => {
                btn.innerHTML = 'Bayar Sekarang';
                btn.disabled = false;
                
                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result){ window.location.reload(); },
                        onPending: function(result){ window.location.reload(); },
                        onError: function(result){ alert("Pembayaran gagal!"); },
                        onClose: function(){ alert("Popup ditutup tanpa pembayaran."); }
                    });
                }
            })
            .catch(error => {
                btn.innerHTML = 'Bayar Sekarang';
                btn.disabled = false;
                alert("Error dari server: " + error.message); // INI BIAR KETAHUAN ERRORNYA APA
            });
        }
    </script>
</x-app-layout>