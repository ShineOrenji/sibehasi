<x-app-layout>
    <div class="pt-8 px-4 max-w-2xl mx-auto sm:px-6 lg:px-8 pb-12">
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><i class="fa-solid fa-user-pen mr-2 text-blue-600"></i>Edit Akun</h1>
                <p class="text-sm text-gray-500">Ubah data untuk {{ $user->name }}.</p>
            </div>
            <a href="{{ route('admin.akun.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg text-sm">Kembali</a>
        </div>

        @if($errors->any()) <div class="mb-4 bg-rose-50 text-rose-700 p-4 rounded-lg font-bold border border-rose-100">{{ $errors->first() }}</div> @endif

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <form action="{{ route('admin.akun.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Role Jabatan</label>
                    <select name="role" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                        <option value="admin" {{ $akun->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="ketua" {{ $user->role == 'ketua' ? 'selected' : '' }}>Ketua HIMASI</option>
                        <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Anggota Biasa</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">NIM</label>
                        <input type="text" inputmode="numeric" pattern="[0-9]*" name="nim" minlength="7" maxlength="8" value="{{ $user->anggota->nim ?? '' }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Angkatan</label>
                        <select name="angkatan" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                            <option value="">-- Pilih Angkatan --</option>
                            @for($i = date('Y'); $i >= 2021; $i--)
                                <option value="{{ $i }}" {{ (isset($user->anggota) && $user->anggota->angkatan == $i) ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="w-full border-gray-300 rounded-lg shadow-sm" required>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</x-app-layout>