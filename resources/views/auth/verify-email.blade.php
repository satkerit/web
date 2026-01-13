<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block">
                    <h1 class="text-3xl font-bold text-green-600">BPRS Bangka Belitung</h1>
                </a>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Verifikasi Email</h2>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-8">
                <p class="text-gray-600 mb-4">
                    Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan ke email Anda.
                </p>

                @if(session('status') == 'verification-link-sent')
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                    Link verifikasi baru telah dikirim ke alamat email Anda.
                </div>
                @endif

                <div class="flex items-center justify-between mt-6">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-green-700 transition">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
