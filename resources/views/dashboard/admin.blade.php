<x-app-layout>
    <div class="space-y-6">
        
        <!-- Header Salam -->
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-sm text-gray-500 font-medium mt-1">Berikut adalah ringkasan keuangan HIMASI hari ini.</p>
        </div>

        <!-- Kartu Statistik (Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Saldo Akhir -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50"></div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Saldo Kas</p>
                    <h3 class="text-2xl font-extrabold text-gray-900 mt-1">Rp{{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center text-xl shadow-lg shadow-blue-500/30 relative z-10">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <!-- Pemasukan Bulan Ini -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full opacity-50"></div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Masuk (Bulan Ini)</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">+Rp{{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl relative z-10">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
            </div>

            <!-- Total Pemasukan -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pemasukan</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center relative z-10">
                    <i class="fa-solid fa-download"></i>
                </div>
            </div>

            <!-- Total Pengeluaran -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pengeluaran</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center relative z-10">
                    <i class="fa-solid fa-upload"></i>
                </div>
            </div>
        </div>

        <!-- Area Grafik Keuangan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900"><i class="fa-solid fa-chart-area text-blue-500 mr-2"></i> Grafik Arus Kas (7 Hari Terakhir)</h3>
            </div>
            <div class="h-80 w-full">
                <canvas id="financeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Script Chart.js -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('financeChart').getContext('2d');
            
            // Gradient Pemasukan (Biru)
            let gradientPemasukan = ctx.createLinearGradient(0, 0, 0, 400);
            gradientPemasukan.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
            gradientPemasukan.addColorStop(1, 'rgba(37, 99, 235, 0)');

            // Gradient Pengeluaran (Merah)
            let gradientPengeluaran = ctx.createLinearGradient(0, 0, 0, 400);
            gradientPengeluaran.addColorStop(0, 'rgba(225, 29, 72, 0.2)');
            gradientPengeluaran.addColorStop(1, 'rgba(225, 29, 72, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! $chartDates->toJson() !!},
                    datasets: [
                        {
                            label: 'Pemasukan (Rp)',
                            data: {!! $chartPemasukan->toJson() !!},
                            borderColor: '#2563eb', // blue-600
                            backgroundColor: gradientPemasukan,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#2563eb',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            fill: true,
                            tension: 0.4 // Membuat garis melengkung smooth
                        },
                        {
                            label: 'Pengeluaran (Rp)',
                            data: {!! $chartPengeluaran->toJson() !!},
                            borderColor: '#e11d48', // rose-600
                            backgroundColor: gradientPengeluaran,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#e11d48',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: "'Figtree', sans-serif", weight: 'bold' },
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleFont: { family: "'Figtree', sans-serif", size: 13 },
                            bodyFont: { family: "'Figtree', sans-serif", size: 14, weight: 'bold' },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9', drawBorder: false },
                            ticks: {
                                font: { family: "'Figtree', sans-serif" },
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000) + ' Jt';
                                    if (value >= 1000) return (value / 1000) + ' Rb';
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { family: "'Figtree', sans-serif", weight: '500' } }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        });
    </script>
</x-app-layout>