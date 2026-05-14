<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal | {{ config('app.name') }}</title>

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
            overflow: hidden;
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
                        url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=2070');
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
        <div class="w-full max-w-6xl glass-card rounded-[40px] overflow-hidden flex flex-col lg:flex-row min-h-[700px]">

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
                        {{ $companyInfo->name ?? config('app.name') }}<br>
                        <span class="text-primary-light">Management System</span>
                    </h1>
                    <p class="text-slate-300 text-lg max-w-md leading-relaxed">
                        Akses aman ke pusat kendali operasional dan manajemen data perbankan syariah yang modern dan terpercaya.
                    </p>
                </div>

                <div class="relative z-10 flex items-center gap-6">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-slate-800" src="https://ui-avatars.com/api/?name=Admin+1&background=0d9488&color=fff" alt="">
                        <img class="w-10 h-10 rounded-full border-2 border-slate-800" src="https://ui-avatars.com/api/?name=Admin+2&background=4f46e5&color=fff" alt="">
                        <img class="w-10 h-10 rounded-full border-2 border-slate-800" src="https://ui-avatars.com/api/?name=Admin+3&background=14b8a6&color=fff" alt="">
                    </div>
                    <p class="text-sm font-medium text-slate-400 italic">
                        "Keamanan data Anda adalah prioritas utama kami."
                    </p>
                </div>

                <!-- Abstract Decorations -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary-light/10 blur-[100px] -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-500/10 blur-[100px] -ml-32 -mb-32"></div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="w-full lg:w-1/2 p-8 sm:p-12 lg:p-20 flex flex-col justify-center bg-slate-900/50">
                <div class="max-w-md w-full mx-auto animate-slide-in">

                    <!-- Mobile Logo -->
                    <div class="lg:hidden flex justify-center mb-8">
                        <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10">
                            <img src="{{ $companyInfo && $companyInfo->logo ? Storage::url($companyInfo->logo) : '' }}" class="w-10 h-10 object-contain" alt="">
                        </div>
                    </div>

                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-white mb-2">Selamat Datang</h2>
                        <p class="text-slate-400">Masuk untuk mengelola sistem Anda</p>
                    </div>

                    @if(session('error'))
                        <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-start gap-3 animate-pulse">
                            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-red-400 leading-tight">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}" x-data="{ showPassword: false }" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="text-xs font-bold uppercase tracking-widest text-slate-500 ml-1">ID Akses / Email</label>
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
                                    class="w-full pl-12 pr-4 py-4 input-glass rounded-2xl text-sm font-medium"
                                    placeholder="nama@bprs.com"
                                >
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs font-bold text-red-400 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between px-1">
                                <label for="password" class="text-xs font-bold uppercase tracking-widest text-slate-500">Kode Sandi</label>
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-primary-light hover:text-white transition-colors">Lupa?</a>
                            </div>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-primary-light transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    required
                                    class="w-full pl-12 pr-12 py-4 input-glass rounded-2xl text-sm font-medium"
                                    placeholder="••••••••"
                                >
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-white transition-colors"
                                    @click="showPassword = !showPassword"
                                >
                                    <svg :class="{ 'hidden': showPassword }" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg :class="{ 'hidden': !showPassword }" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Captcha -->
                        <div class="p-6 rounded-[24px] bg-white/5 border border-white/10 flex items-center justify-between gap-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-light mb-1">Verify Security</span>
                                <span class="text-2xl font-black text-white tracking-tighter">{{ $captcha_question }}</span>
                            </div>
                            <div class="w-32">
                                <input
                                    type="number"
                                    name="captcha_answer"
                                    required
                                    class="w-full px-4 py-3 input-glass rounded-xl text-center text-xl font-bold"
                                    placeholder="?"
                                >
                            </div>
                        </div>

                        <!-- Remember -->
                        <div class="flex items-center px-1">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="remember" class="peer sr-only">
                                <div class="w-5 h-5 rounded-lg bg-white/5 border border-white/20 peer-checked:bg-primary peer-checked:border-primary transition-all flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white scale-0 peer-checked:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="ml-3 text-sm font-semibold text-slate-400 group-hover:text-white transition-colors">Tetap masuk</span>
                            </label>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn-glow w-full py-4 rounded-2xl text-white font-extrabold text-base tracking-wide flex items-center justify-center gap-3">
                            <span>Masuk ke Dashboard</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="mt-12 flex items-center justify-between text-slate-500">
                        <a href="{{ route('home') }}" class="text-sm font-bold hover:text-primary-light transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Web Beranda
                        </a>
                        <span class="text-xs font-bold tracking-tighter">SECURE NODE: {{ request()->ip() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile System Info -->
        <p class="lg:hidden absolute bottom-6 text-center text-[10px] font-black uppercase tracking-[0.3em] text-slate-600">
            &copy; {{ date('Y') }} {{ config('app.name') }} &bull; System Verified
        </p>
    </div>
</body>
</html>
