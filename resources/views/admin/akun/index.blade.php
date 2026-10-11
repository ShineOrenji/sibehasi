<x-app-layout>
    <div class="pt-8 px-4 max-w-7xl mx-auto sm:px-6 lg:px-8 pb-12">
        
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900"><i class="fa-solid fa-users-gear mr-2 text-blue-600"></i>Kelola Akun</h1>
            <p class="text-sm text-gray-500">Kelola akun Anggota & Ketua HIMASI.</p>
        </div>

        @if(session('success')) <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg font-bold border border-emerald-100">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="mb-4 bg-rose-50 text-rose-700 p-4 rounded-lg font-bold border border-rose-100">{{ session('error') }}</div> @endif
        @if($errors->any()) <div class="mb-4 bg-rose-50 text-rose-700 p-4 rounded-lg font-bold border border-rose-100">{{ $errors->first() }}</div> @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Tambah Akun -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-fit">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Buat Akun Baru</h3>
                <form action="{{ route('admin.akun.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Role Jabatan</label>
                        <select name="role" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            <option value="admin">Admin</option>
                            <option value="ketua">Ketua HIMASI</option>
                            <option value="mahasiswa">Anggota Biasa</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">NIM</label>
                        <input type="text" inputmode="numeric" pattern="[0-9]*" name="nim" minlength="7" maxlength="8" class="w-full border-gray-300 rounded-lg shadow-sm" required placeholder="Contoh: 24050003">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Angkatan (Tahun)</label>
                        <select name="angkatan" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            <option value="">-- Pilih Angkatan --</option>
                            @for($i = date('Y'); $i >= 2021; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password Awal</label>
                        <input type="text" name="password" value="himasi26" class="w-full border-gray-300 rounded-lg shadow-sm text-gray-500" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition">Buat Akun</button>
                </form>
            </div>

            <!-- Tabel Data Mahasiswa -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Daftar Akun</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                                <th class="p-3 font-semibold">Nama / NIM</th>
                                <th class="p-3 font-semibold">Email & Role</th>
                                <th class="p-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $u)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3">
                                    <p class="text-sm font-bold text-gray-900">{{ $u->name }}</p>
                                    <p class="text-xs text-gray-500 font-semibold">NIM: {{ $u->anggota->nim ?? '-' }} | Thn: {{ $u->anggota->angkatan ?? '-' }}</p>
                                </td>
                                <td class="p-3">
                                    <p class="text-sm text-gray-600">{{ $u->email }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold rounded-full {{ $u->role == 'ketua' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ strtoupper($u->role == 'mahasiswa' ? 'ANGGOTA' : $u->role) }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Tombol Reset Pass -->
                                        <form action="{{ route('admin.akun.reset', $u->id) }}" method="POST" onsubmit="return confirm('Reset password akun ini ke default (himasi26)?');">
                                            @csrf
                                            <input type="hidden" name="password" value="himasi26">
                                            <button type="submit" title="Reset Password" class="text-xs bg-gray-100 text-gray-600 hover:bg-gray-200 px-2.5 py-1.5 rounded-lg font-semibold transition">
                                                <i class="fa-solid fa-key"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.akun.edit', $u->id) }}" title="Edit" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg font-semibold transition">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.akun.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini secara permanen?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus" class="text-xs bg-rose-50 text-rose-600 hover:bg-rose-100 px-2.5 py-1.5 rounded-lg font-semibold transition">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
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