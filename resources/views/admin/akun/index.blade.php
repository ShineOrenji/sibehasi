<x-app-layout>
    <div class="pt-8 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 pb-12">
        
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900"><i class="fa-solid fa-users-gear mr-2 text-blue-600"></i>Kelola Akun Mahasiswa</h1>
            <p class="text-sm text-gray-500">Buat akun baru atau reset password mahasiswa yang lupa.</p>
        </div>

        @if(session('success')) <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg font-bold border border-emerald-100">{{ session('success') }}</div> @endif
        @if($errors->any()) <div class="mb-4 bg-rose-50 text-rose-700 p-4 rounded-lg font-bold border border-rose-100">{{ $errors->first() }}</div> @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Tambah Akun -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-fit">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Buat Akun Baru</h3>
                <form action="{{ route('akun.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">NIM</label>
                        <input type="text" inputmode="numeric" pattern="[0-9]*" name="nim" minlength="7" maxlength="8" class="w-full border-gray-300 rounded-lg shadow-sm" required placeholder="Contoh: 24050003">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password Awal</label>
                        <input type="text" name="password" value="password123" class="w-full border-gray-300 rounded-lg shadow-sm text-gray-500" required>
                        <span class="text-xs text-gray-400 mt-1 block">Default: password123</span>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition">Buatkan Akun</button>
                </form>
            </div>

            <!-- Tabel Data Mahasiswa -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Daftar Akun Mahasiswa</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                                <th class="p-3 font-semibold">Nama / NIM</th>
                                <th class="p-3 font-semibold">Email</th>
                                <th class="p-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $u)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3">
                                    <p class="text-sm font-bold text-gray-900">{{ $u->name }}</p>
                                    <p class="text-xs text-blue-600 font-semibold">{{ $u->anggota->nim ?? '-' }}</p>
                                </td>
                                <td class="p-3 text-sm text-gray-600">{{ $u->email }}</td>
                                <td class="p-3 text-right">
                                    <!-- Form Reset Password Langsung -->
                                    <form action="{{ route('akun.reset', $u->id) }}" method="POST" class="flex justify-end items-center gap-2" onsubmit="return confirm('Yakin ingin mereset password akun ini ke default (password123)?');">
                                        @csrf
                                        <input type="hidden" name="password" value="password123">
                                        <button type="submit" class="text-xs bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white px-3 py-1.5 rounded-lg font-semibold transition border border-rose-100">
                                            <i class="fa-solid fa-key mr-1"></i> Reset Pass
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>