<x-app-layout>
    <div class="pt-8 px-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Keuangan</h1>
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
                <p class="text-sm text-gray-500">Ringkasan kas HIMASI hari ini.</p>
            </div>

            <!-- Cards Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Saldo</p>
                    <h3 class="text-3xl font-bold text-gray-800">Rp{{ number_format($saldo, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pemasukan</p>
                    <h3 class="text-3xl font-bold text-emerald-600">Rp{{ number_format($pemasukan, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pengeluaran</p>
                    <h3 class="text-3xl font-bold text-rose-600">Rp{{ number_format($pengeluaran, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Anggota Aktif</p>
                    <h3 class="text-3xl font-bold text-blue-600">{{ $totalAnggota }} <span class="text-sm font-normal text-gray-400">Orang</span></h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Grafik (Chart.js) -->
                <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Keuangan</h3>
                    <canvas id="financeChart" height="100"></canvas>
                </div>

                <!-- Transaksi Terbaru -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Transaksi Terbaru</h3>
                    <div class="space-y-4">
                        @forelse($transaksiTerbaru as $t)
                        <div class="flex justify-between items-center pb-3 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $t->keterangan }}</p>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d M Y') }}</p>
                            </div>
                            <span class="text-sm font-bold {{ $t->jenis == 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $t->jenis == 'pemasukan' ? '+' : '-' }}Rp{{ number_format($t->nominal, 0, ',', '.') }}
                            </span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">Belum ada transaksi.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('financeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    label: 'Nominal (Rp)',
                    data: [{{ $pemasukan }}, {{ $pengeluaran }}],
                    backgroundColor: ['#10b981', '#f43f5e'],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, grid: { borderDash: [2, 4] } } }
            }
        });
    </script>
</x-app-layout>