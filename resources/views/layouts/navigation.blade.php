<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-lg border-b border-gray-100 sticky top-0 z-50 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- BAGIAN KIRI (Logo & Menu Desktop) -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-extrabold text-2xl tracking-tight text-blue-600 flex items-center gap-2 hover:scale-105 transition-transform">
                        <img src="{{ asset('assets/images/himasi.png') }}" alt="Logo" class="h-10 w-auto">
                        SIBEHASI
                    </a>
                </div>
                
                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-8 lg:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="hover:text-blue-600 transition-colors px-3 font-semibold">
                        <i class="fa-solid fa-house mr-2 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i> Dashboard
                    </x-nav-link>

                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.kas.index')" :active="request()->routeIs('admin.kas.index')" class="px-3 font-semibold">
                            <i class="fa-solid fa-book mr-2 {{ request()->routeIs('admin.kas.index') ? 'text-blue-600' : 'text-gray-400' }}"></i> Buku Kas
                        </x-nav-link>
                        <x-nav-link :href="route('admin.pembayaran-cash.create')" :active="request()->routeIs('admin.pembayaran-cash.*')" class="px-3 font-semibold">
                            <i class="fa-solid fa-hand-holding-dollar mr-2 {{ request()->routeIs('admin.pembayaran-cash.*') ? 'text-blue-600' : 'text-gray-400' }}"></i> Input Cash
                        </x-nav-link>
                        <x-nav-link :href="route('admin.pengeluaran.create')" :active="request()->routeIs('admin.pengeluaran.*')" class="px-3 font-semibold">
                            <i class="fa-solid fa-money-bill-transfer mr-2 {{ request()->routeIs('admin.pengeluaran.*') ? 'text-blue-600' : 'text-gray-400' }}"></i> Pengeluaran
                        </x-nav-link>
                        <x-nav-link :href="route('admin.periode.index')" :active="request()->routeIs('admin.periode.*')" class="px-3 font-semibold">
                            <i class="fa-solid fa-calendar-check mr-2 {{ request()->routeIs('admin.periode.*') ? 'text-blue-600' : 'text-gray-400' }}"></i> Tagihan
                        </x-nav-link>
                        <x-nav-link :href="route('admin.kategori.index')" :active="request()->routeIs('admin.kategori.*')" class="px-3 font-semibold">
                            <i class="fa-solid fa-tags mr-2 {{ request()->routeIs('admin.kategori.*') ? 'text-blue-600' : 'text-gray-400' }}"></i> Kategori
                        </x-nav-link>
                        <x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')" class="px-3 font-semibold">
                            <i class="fa-solid fa-chart-pie mr-2 {{ request()->routeIs('admin.laporan.*') ? 'text-blue-600' : 'text-gray-400' }}"></i> Laporan
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- BAGIAN KANAN (Profil Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-bold rounded-full text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none transition ease-in-out duration-150 shadow-sm border-gray-200">
                            <div class="w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center mr-2 text-xs">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            {{ Auth::user()->name }}
                            <div class="ms-1">
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 ml-1"></i>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-medium">
                            <i class="fa-regular fa-user mr-2 w-4 text-gray-400"></i> Profil Saya
                        </x-dropdown-link>
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'ketua')
                            <x-responsive-nav-link :href="route('admin.akun.index')" class="rounded-lg font-semibold text-blue-600 hover:text-blue-700">
                                <i class="fa-solid fa-users-gear mr-2 w-5 text-center"></i> Kelola Akun
                            </x-responsive-nav-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 hover:bg-rose-50 font-bold">
                                <i class="fa-solid fa-arrow-right-from-bracket mr-2 w-4"></i> Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- TOMBOL HAMBURGER (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-blue-600 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- MENU DROPDOWN MOBILE (Muncul saat Hamburger diklik) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-b border-gray-200 shadow-xl absolute w-full left-0 transition-all">
        <div class="pt-2 pb-3 space-y-1 px-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-lg font-semibold">
                <i class="fa-solid fa-house mr-2 w-5 text-center"></i> Dashboard
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.kas.index')" :active="request()->routeIs('admin.kas.index')" class="rounded-lg font-semibold">
                    <i class="fa-solid fa-book mr-2 w-5 text-center"></i> Buku Kas
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.pembayaran-cash.create')" :active="request()->routeIs('admin.pembayaran-cash.*')" class="rounded-lg font-semibold">
                    <i class="fa-solid fa-hand-holding-dollar mr-2 w-5 text-center"></i> Input Cash
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.pengeluaran.create')" :active="request()->routeIs('admin.pengeluaran.*')" class="rounded-lg font-semibold">
                    <i class="fa-solid fa-money-bill-transfer mr-2 w-5 text-center"></i> Pengeluaran
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.periode.index')" :active="request()->routeIs('admin.periode.*')" class="rounded-lg font-semibold">
                    <i class="fa-solid fa-calendar-check mr-2 w-5 text-center"></i> Kelola Tagihan
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.kategori.index')" :active="request()->routeIs('admin.kategori.*')" class="rounded-lg font-semibold">
                    <i class="fa-solid fa-tags mr-2 w-5 text-center"></i> Master Kategori
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')" class="rounded-lg font-semibold">
                    <i class="fa-solid fa-chart-pie mr-2 w-5 text-center"></i> Laporan Keuangan
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Profil Mobile -->
        <div class="pt-4 pb-3 border-t border-gray-100 bg-gray-50">
            <div class="px-4 flex items-center mb-3">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-lg mr-3 shadow-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-base text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="space-y-1 px-3">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-lg font-semibold">
                    <i class="fa-regular fa-user mr-2 w-5 text-center"></i> Profil Saya
                </x-responsive-nav-link>
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'ketua')
                    <x-responsive-nav-link :href="route('admin.akun.index')" class="rounded-lg font-semibold text-blue-600">
                        <i class="fa-solid fa-users-gear mr-2 w-5 text-center"></i> Kelola Akun
                    </x-responsive-nav-link>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 rounded-lg hover:bg-rose-50 font-bold">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2 w-5 text-center"></i> Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>