<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIBEHASI - Sistem Informasi Bendahara</title>
    <!-- Font & Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/himasi.png') }}">
    {{-- CSS vanilla --}}
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="login flex items-center justify-center min-h-screen">
    
    <div class="max-w-md w-full px-6">
        <div class="bg-white p-8 rounded-2xl shadow-xl shadow-blue-900/5 border border-gray-100 text-center">
            
            <div class="w-23 h-23 flex items-center justify-center text-2xl mx-auto mb-2">
                <img src="{{ asset('assets/images/himasi.png') }}" alt="Logo" class="w-auto h-full">
            </div>
            
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">SIBEHASI</h1>
            <p class="text-sm text-gray-500 mb-8">Sistem Informasi Bendahara HIMASI.<br>Kelola iuran dan kas dengan transparan dan efisien.</p>
            
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md shadow-blue-600/20">
                        Masuk ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block w-full bg-gray-900 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md shadow-gray-900/20 mb-3">
                        Login Akun
                    </a>
                    <p class="text-xs text-gray-400 mt-4">Belum punya akun? Hubungi Admin / Ketua untuk pembuatan akun.</p>
                @endauth
            @endif
        </div>
        
        <p class="text-center text-xs text-gray-400 mt-8 font-medium">&copy; {{ date('Y') }} HIMASI. All rights reserved.</p>
    </div>

</body>
</html>