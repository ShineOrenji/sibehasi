<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIBEHASI') }}</title>

        <!-- Fonts & FontAwesome -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

         {{-- CSS vanilla --}}
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

        <!-- Scripts (Pastikan Alpine.js bawaan Laravel/Breeze terpanggil di app.js) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
        
        <!-- Panggil Navigasi (Sidebar & Topbar) -->
        @include('layouts.navigation')

        <!-- Main Content Area -->
        <!-- lg:ml-64 untuk memberi ruang sidebar di PC, pt-16 untuk memberi ruang topbar -->
        <div class="transition-all duration-300 lg:ml-64 pt-16 min-h-screen flex flex-col">
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>

        <!-- SweetAlert2 (Pop-up Alert Keren) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: { popup: 'rounded-2xl shadow-xl' }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: { popup: 'rounded-2xl shadow-xl' }
            });
        @endif
    </script>
</body>

<script>
    function konfirmasiHapus(formId, pesan = 'Data ini akan dihapus permanen!') {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-2xl shadow-xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        })
    }
</script>

<script>
        // ... (Script Swal Alert Sukses/Error yang tadi) ...

        // Script untuk Konfirmasi Hapus Notifikasi
        function hapusNotif(id) {
            Swal.fire({
                title: 'Hapus Notifikasi?',
                text: "Notifikasi ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl shadow-xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-hapus-notif-' + id).submit();
                }
            })
        }
    </script>
        
    </body>
</html>