<!-- SIDEBAR (Desktop Fixed, Mobile Slide) -->
<aside class="fixed inset-y-0 left-0 x-cloak bg-slate-900 text-slate-300 w-64 z-50 transform transition-transform duration-300 shadow-2xl flex flex-col" :class="{'translate-x-0': sidebarOpen, '-translate-x-full lg:translate-x-0': !sidebarOpen}">
    <div class="h-16 flex items-center justify-center bg-slate-950 border-b border-slate-800">
        <a href="{{ route('dashboard') }}" class="font-extrabold text-2xl tracking-tight text-white flex items-center">
            <img src="{{ asset('assets/images/himasi.png') }}" alt="Logo" class="w-14 h-auto">SIBEHASI
        </a>
    </div>
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-2 ml-2">Menu Utama</p>
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
            <i class="fa-solid fa-house w-6 text-center"></i> Dashboard
        </a>
        @if(Auth::user()->role === 'admin')
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2 ml-2">Keuangan</p>
            <a href="{{ route('admin.konfirmasi.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.konfirmasi.*') ? 'bg-rose-600 text-white shadow-md shadow-rose-900/50' : 'hover:bg-slate-800 hover:text-white text-rose-400' }}">
                <i class="fa-solid fa-bell w-6 text-center"></i> Konfirmasi Tagihan
            </a>
            <a href="{{ route('admin.pembayaran-cash.create') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.pembayaran-cash.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-hand-holding-dollar w-6 text-center"></i> Input Cash
            </a>
            <a href="{{ route('admin.pengeluaran.create') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.pengeluaran.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-cart-shopping w-6 text-center"></i> Pengeluaran
            </a>
            <a href="{{ route('admin.kas.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.kas.index') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-book w-6 text-center"></i> Buku Kas
            </a>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2 ml-2">Master & Laporan</p>
            <a href="{{ route('admin.periode.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.periode.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-calendar-check w-6 text-center"></i> Kelola Tagihan
            </a>
            <a href="{{ route('admin.kategori.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.kategori.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-tags w-6 text-center"></i> Master Kategori
            </a>
            <a href="{{ route('admin.akun.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.akun.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-users-gear w-6 text-center"></i> Kelola Akun
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.laporan.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-6 text-center"></i> Laporan Keuangan
            </a>
        @elseif(Auth::user()->role === 'ketua')
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2 ml-2">Master Data</p>
            <a href="{{ route('admin.akun.index') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.akun.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-users-gear w-6 text-center"></i> Kelola Akun
            </a>
        @endif

        @if(Auth::user()->role === 'mahasiswa')
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-6 mb-2 ml-2">Menu Saya</p>
            <a href="{{ route('mahasiswa.riwayat') }}" class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('mahasiswa.riwayat') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-clock-rotate-left w-6 text-center"></i> Riwayat Saya
            </a>
        @endif
    </div>
    <div class="p-4 border-t border-slate-800 bg-slate-950">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-400 font-bold tracking-widest">{{ strtoupper(Auth::user()->role) }}</p>
            </div>
        </div>
    </div>
</aside>

<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden" style="display: none;"></div>

<header class="fixed top-0 right-0 left-0 lg:left-64 h-16 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm z-30 flex items-center justify-between px-4 sm:px-6 transition-all duration-300">
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-blue-600 focus:outline-none bg-gray-50 p-2 rounded-lg transition-colors">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <h2 class="hidden sm:block ml-2 text-lg font-extrabold text-gray-800 capitalize">{{ str_replace('-', ' ', request()->segment(2) ?? 'Dashboard') }}</h2>
    </div>

    <div class="flex items-center gap-4">
        @if(Auth::user()->role === 'admin')
            @php
                $pendingCount = \App\Models\PembayaranIuran::where('status', 'pending')->count();
                $pendingData = \App\Models\PembayaranIuran::with(['anggota.user', 'periode'])->where('status', 'pending')->orderBy('created_at', 'desc')->take(5)->get();
            @endphp
            <x-dropdown align="right" width="100">
                <x-slot name="trigger">
                    <button class="relative p-2 text-gray-500 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 rounded-full transition-colors focus:outline-none">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @if($pendingCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 text-[9px] font-bold text-white bg-rose-500 border-2 border-white rounded-full animate-pulse">{{ $pendingCount }}</span>
                        @endif
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="p-3 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-md">
                        <span class="font-bold text-gray-800">Menunggu Konfirmasi</span>
                        <span class="text-xs bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full font-bold">{{ $pendingCount }} Antrean</span>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        @forelse($pendingData as $notif)
                            <a href="{{ route('admin.konfirmasi.index') }}" class="block p-4 border-b border-gray-50 hover:bg-blue-50 transition-colors">
                                <p class="text-sm font-bold text-gray-800 mb-1"><i class="fa-solid fa-file-invoice-dollar text-blue-500 mr-1"></i> {{ $notif->anggota->user->name }}</p>
                                <p class="text-xs text-gray-500">Membayar tagihan <b>{{ $notif->periode->nama_periode }}</b>.</p>
                                <p class="text-[10px] font-semibold text-blue-600 mt-2">{{ \Carbon\Carbon::parse($notif->tanggal_bayar)->diffForHumans() }}</p>
                            </a>
                        @empty
                            <div class="p-6 text-center text-gray-500">
                                <i class="fa-solid fa-circle-check text-2xl mb-2 text-emerald-400"></i>
                                <p class="text-sm font-semibold">Semua tagihan sudah beres.</p>
                            </div>
                        @endforelse
                    </div>
                    @if($pendingCount > 0)
                        <a href="{{ route('admin.konfirmasi.index') }}" class="block p-3 text-center text-sm font-bold text-blue-600 hover:bg-gray-50 border-t border-gray-100 rounded-b-md">Proses Semua <i class="fa-solid fa-arrow-right ml-1"></i></a>
                    @endif
                </x-slot>
            </x-dropdown>
        @endif

        @if(Auth::user()->role === 'mahasiswa')
            @php
                $unreadNotifs = Auth::user()->unreadNotifications;
                $notifCount = $unreadNotifs->count();
            @endphp
            <x-dropdown align="right" width="100">
                <x-slot name="trigger">
                    <button class="relative p-2 text-gray-500 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 rounded-full transition-colors focus:outline-none">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @if($notifCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 text-[9px] font-bold text-white bg-blue-600 border-2 border-white rounded-full animate-pulse">{{ $notifCount }}</span>
                        @endif
                    </button>
                </x-slot>
                <x-slot name="content">
                    <div class="p-3 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-md">
                        <span class="font-bold text-gray-800">Notifikasi Saya</span>
                        @if($notifCount > 0)
                            <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-bold">{{ $notifCount }} Baru</span>
                        @endif
                    </div>
                    <div class="max-h-64 overflow-y-auto custom-scrollbar">
                        @forelse($unreadNotifs as $notif)
                            <div class="relative group p-4 border-b border-gray-100 bg-white hover:bg-slate-50 transition-colors flex flex-col gap-1 pr-12">
                                <p class="text-sm font-extrabold text-gray-800">{{ $notif->data['title'] ?? 'Informasi' }}</p>
                                <p class="text-xs text-gray-600 leading-relaxed">{{ $notif->data['message'] ?? '' }}</p>
                                <p class="text-[10px] font-bold text-blue-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                
                                <form action="{{ route('notifikasi.destroy', $notif->id) }}" method="POST" id="form-hapus-notif-{{ $notif->id }}" class="absolute right-4 top-1/2 transform -translate-y-1/2 opacity-50 hover:opacity-100 transition-opacity">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="hapusNotif('{{ $notif->id }}')" class="w-8 h-8 flex items-center justify-center bg-rose-100 text-rose-600 rounded-full hover:bg-rose-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <i class="fa-regular fa-bell-slash text-3xl mb-3 text-gray-300"></i>
                                <p class="text-sm font-medium">Belum ada notifikasi.</p>
                            </div>
                        @endforelse
                    </div>
                </x-slot>
            </x-dropdown>
        @endif

        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-bold rounded-full text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none transition ease-in-out duration-150 shadow-sm border-gray-200">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563eb&color=fff&bold=true" class="w-7 h-7 rounded-full mr-2 shadow-sm">
                    <span class="hidden sm:inline">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 sm:ml-2"></i>
                </button>
            </x-slot>
            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')" class="font-medium text-gray-700 hover:text-blue-600">
                    <i class="fa-regular fa-user mr-2 w-4 text-center"></i> Profil Saya
                </x-dropdown-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="font-bold text-rose-600 hover:bg-rose-50 hover:text-rose-700">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2 w-4 text-center"></i> Keluar
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>