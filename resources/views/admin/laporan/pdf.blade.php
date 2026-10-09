<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan HIMASI</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; }
        .subtitle { font-size: 14px; margin: 5px 0 0 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary-box { margin-top: 20px; width: 50%; float: right; }
        .summary-table th { background-color: #fff; text-align: left; border: none; padding: 4px; }
        .summary-table td { border: none; text-align: right; padding: 4px; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">LAPORAN KEUANGAN HIMASI</h1>
        <p class="subtitle">Periode: {{ $namaBulan }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="35%">Keterangan</th>
                <th width="15%">Kategori</th>
                <th width="15%" class="text-right">Pemasukan</th>
                <th width="15%" class="text-right">Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $t)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d/m/Y') }}</td>
                <td>{{ $t->keterangan }}</td>
                <td>{{ $t->kategori->nama_kategori ?? '-' }}</td>
                <td class="text-right">{{ $t->jenis == 'pemasukan' ? number_format($t->nominal, 0, ',', '.') : '-' }}</td>
                <td class="text-right">{{ $t->jenis == 'pengeluaran' ? number_format($t->nominal, 0, ',', '.') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <th>Total Pemasukan</th>
                <td>: Rp {{ number_format($pemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Pengeluaran</th>
                <td>: Rp {{ number_format($pengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th><strong>Saldo Bersih</strong></th>
                <td><strong>: Rp {{ number_format($saldo, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>

</body>
</html>