<x-app-layout>
    <div class="max-w-4xl mx-auto py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-4">Input Pembayaran Cash</h2>
            
            @if(session('error')) <div class="text-red-500 mb-4">{{ session('error') }}</div> @endif
            
            <form action="{{ route('admin.pembayaran-cash.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Pilih Anggota</label>
                    <select id="select-anggota" name="anggota_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($anggota as $a)
                            <option value="{{ $a->id }}">{{ $a->user->name }} ({{ $a->nim }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Periode Iuran</label>
                    <select name="periode_id" class="w-full border-gray-300 rounded-lg">
                        @foreach(\App\Models\PeriodeIuran::where('status', 'aktif')->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_periode }} - Rp{{ number_format($p->nominal_akhir, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Simpan Pembayaran</button>
            </form>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect("#select-anggota", {
            create: false,
            placeholder: "Ketik nama atau NIM anggota...",
            sortField: { field: "text", direction: "asc" }
        });
    </script>
</x-app-layout>