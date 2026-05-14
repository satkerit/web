<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun | {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/alpine-bundle.js'])

    @php $nonce = request()->attributes->get('csp_nonce'); @endphp

    <style nonce="{{ $nonce }}">
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #2dd4bf;
            --bg-canvas: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-canvas);
            overflow-x: hidden;
        }

        .mesh-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-color: #0f172a;
            background-image:
                radial-gradient(at 0% 0%, rgba(13, 148, 136, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(79, 70, 229, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(13, 148, 136, 0.15) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(79, 70, 229, 0.1) 0px, transparent 50%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .input-glass {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-glass:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(45, 212, 191, 0.1);
            outline: none;
        }

        .btn-glow {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.4);
            transition: all 0.3s ease;
        }

        .btn-glow:hover {
            box-shadow: 0 8px 25px rgba(13, 148, 136, 0.6);
            transform: translateY(-2px);
        }

        .floating-blob {
            position: absolute;
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.2) 0%, rgba(79, 70, 229, 0.2) 100%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
            animation: float 20s infinite alternate;
        }

        .blob-1 { top: -10%; left: -10%; }
        .blob-2 { bottom: -10%; right: -10%; animation-delay: -5s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 50px) scale(1.1); }
        }

        .side-visual {
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%),
                        url('https://images.unsplash.com/photo-1454165833767-027ffea9e778?auto=format&fit=crop&q=80&w=2070');
            background-size: cover;
            background-position: center;
        }

        [x-cloak] { display: none !important; }

        @keyframes slideIn {
            from { transform: translateX(30px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .animate-slide-in {
            animation: slideIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="antialiased text-slate-200">
    <div class="mesh-gradient"></div>
    <div class="floating-blob blob-1"></div>
    <div class="floating-blob blob-2"></div>

    @php
        $companyInfo = \App\Models\CompanyInfo::getInfo();
    @endphp

    <div class="min-h-screen flex items-center justify-center p-4 lg:p-0">
        <div class="w-full max-w-6xl glass-card rounded-[40px] overflow-hidden flex flex-col lg:flex-row min-h-[800px]">

            <!-- Left Side: Visual & Branding -->
            <div class="hidden lg:flex lg:w-1/2 side-visual p-16 flex-col justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 mb-8">
                        @if($companyInfo && $companyInfo->logo)
                            <img src="{{ Storage::url($companyInfo->logo) }}" alt="Logo" class="w-10 h-10 object-contain">
                        @else
                            <svg class="w-10 h-10 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        @endif
                    </div>
                    <h1 class="text-4xl font-extrabold text-white leading-tight mb-4">
                        Mulai Perjalanan<br>
                        <span class="text-primary-light">Digital Anda</span>
                    </h1>
                    <p class="text-slate-300 text-lg max-w-md leading-relaxed">
                        Bergabunglah dengan tim kami untuk mengelola sistem perbankan syariah yang inovatif dan aman.
                    </p>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center border border-primary/30">
                            <svg class="w-6 h-6 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-bold">Keamanan Terjamin</h4>
                            <p class="text-slate-400 text-sm">Enkripsi data standar industri perbankan.</p>
                        </div>
                    </div>
                </div>

                <!-- Abstract Decorations -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary-light/10 blur-[100px] -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-500/10 blur-[100px] -ml-32 -mb-32"></div>
            </div>

            <!-- Right Side: Registration Form -->
            <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-20 flex flex-col justify-center bg-slate-900/50">
                <div class="max-w-md w-full mx-auto animate-slide-in">

                    <div class="mb-10 text-center lg:text-left">
                        <h2 class="text-3xl font-bold text-white mb-2">Daftar Akun Baru</h2>
                        <p class="text-slate-400">Silakan lengkapi data diri Anda</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <!-- Name -->
                        <div class="space-y-2">
                            <label for="name" class="text-xs font-bold uppercase tracking-widest text-slate-500 ml-1">Nama Lengkap</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-primary-light transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    class="w-full pl-12 pr-4 py-3.5 input-glass rounded-2xl text-sm font-medium"
                                    placeholder="Masukkan nama Anda"
                                >
                            </div>
                            @error('name')
                                <p class="mt-1 text-xs font-bold text-red-400 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="text-xs font-bold uppercase tracking-widest text-slate-500 ml-1">Alamat Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-primary-light transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full pl-12 pr-4 py-3.5 input-glass rounded-2xl text-sm font-medium"
                                    placeholder="nama@perusahaan.com"
                                >
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs font-bold text-red-400 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="text-xs font-bold uppercase tracking-widest text-slate-500 ml-1">Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-primary-light transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    class="w-full pl-12 pr-4 py-3.5 input-glass rounded-2xl text-sm font-medium"
                                    placeholder="Minimal 8 karakter"
                                >
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs font-bold text-red-400 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-xs font-bold uppercase tracking-widest text-slate-500 ml-1">Konfirmasi Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-primary-light transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    class="w-full pl-12 pr-4 py-3.5 input-glass rounded-2xl text-sm font-medium"
                                    placeholder="Ulangi password"
                                >
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn-glow w-full py-4 rounded-2xl text-white font-extrabold text-base tracking-wide flex items-center justify-center gap-3 mt-4">
                            <span>Daftar Sekarang</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9l-6 6-6-6"/>
                            </svg>
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="mt-8 text-center">
                        <p class="text-sm font-semibold text-slate-400">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-primary-light hover:text-white transition-colors">Masuk di sini</a>
                        </p>
                    </div>

                    <div class="mt-10 flex items-center justify-center text-slate-500">
                        <a href="{{ route('home') }}" class="text-sm font-bold hover:text-primary-light transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Web Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
