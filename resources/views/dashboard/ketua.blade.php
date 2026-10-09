<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Monitoring Ketua</h1>
                <p class="text-sm text-gray-500">Pantauan ringkasan kas dan transparansi keuangan HIMASI.</p>
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Transaksi Terbaru</h3>
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