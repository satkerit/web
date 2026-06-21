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
    @vite(['resources/css/admin.css', 'resources/js/admin.js', 'resources/js/alpine-bundle.js'])

    @php $nonce = request()->attributes->get('csp_nonce'); @endphp

    <style nonce="{{ $nonce }}">
        :root {
            --primary: #0f766e;
            --primary-dark: #0d5f58;
            --primary-light: #14b8a6;
            --bg-canvas: #020617;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-canvas);
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(30, 64, 175, 0.2);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.8),
                        0 0 0 1px rgba(15, 23, 42, 0.5);
        }

        .input-group {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(30, 64, 175, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-group:focus-within {
            background: rgba(15, 23, 42, 0.9);
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            box-shadow: 0 4px 14px rgba(20, 184, 166, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            box-shadow: 0 10px 30px rgba(20, 184, 166, 0.45);
            transform: translateY(-2px);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 4px 14px rgba(20, 184, 166, 0.3);
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(148, 163, 184, 0.4);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .custom-checkbox.checked {
            background: var(--primary);
            border-color: var(--primary);
        }

        [x-cloak] {
            display: none !important;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(20, 184, 166, 0.15);
            }
            50% {
                box-shadow: 0 0 40px rgba(20, 184, 166, 0.3);
            }
        }

        .animate-pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="antialiased text-slate-100 min-h-screen">
    <!-- Animated Background -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl animate-float"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-cyan-500/15 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-br from-emerald-500/5 to-cyan-500/5 rounded-full blur-3xl"></div>
    </div>

    @php
        $companyInfo = \App\Models\CompanyInfo::getInfo();
    @endphp

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-5xl">
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl flex flex-col lg:flex-row min-h-[600px]">

                <!-- Left Side: Branding -->
                <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-900/95 to-slate-900 p-12 flex-col justify-between relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl -mt-20 -mr-20"></div>
                    <div class="absolute bottom-0 left-0 w-72 h-72 bg-cyan-500/10 rounded-full blur-3xl -mb-20 -ml-20"></div>

                    <div class="relative z-10">
                        <!-- Logo -->
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/25">
                                @if($companyInfo && $companyInfo->logo)
                                    <img src="{{ Storage::url($companyInfo->logo) }}" alt="Logo" class="w-9 h-9 object-contain">
                                @else
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h1 class="text-2xl font-extrabold text-white tracking-tight">{{ $companyInfo->name ?? config('app.name') }}</h1>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Admin Portal</p>
                            </div>
                        </div>

                        <!-- Headline -->
                        <h2 class="text-3xl font-extrabold text-white leading-tight mb-4">
                            Selamat Datang Kembali
                        </h2>
                        <p class="text-slate-400 text-lg leading-relaxed max-w-sm">
                            Akses aman ke sistem manajemen perbankan syariah. Selalu lindungi kredensial Anda.
                        </p>
                    </div>

                    <!-- Features -->
                    <div class="relative z-10 space-y-4">
                        <div class="flex items-center gap-3 p-4 bg-white/5 rounded-2xl border border-white/10">
                            <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-white">Keamanan Terjamin</h3>
                                <p class="text-xs text-slate-500">Enkripsi end-to-end untuk semua data</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-white/5 rounded-2xl border border-white/10">
                            <div class="w-10 h-10 bg-cyan-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-white">Performa Optimal</h3>
                                <p class="text-xs text-slate-500">Sistem yang cepat dan responsif</p>
                            </div>
                        </div>
                    </div>

                    <!-- Copyright -->
                    <div class="relative z-10 text-xs text-slate-600 font-medium">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.
                    </div>
                </div>

                <!-- Right Side: Login Form -->
                <div class="w-full lg:w-1/2 p-8 sm:p-12 flex flex-col justify-center bg-slate-950/50">
                    <div class="max-w-md w-full mx-auto" x-data="{
                        showPassword: false,
                        remember: {{ old('remember') ? 'true' : 'false' }},
                        isLoading: false
                    }" x-init="console.log('Alpine initialized')">

                        <!-- Mobile Logo -->
                        <div class="lg:hidden flex items-center justify-center mb-10">
                            <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center animate-pulse-glow">
                                @if($companyInfo && $companyInfo->logo)
                                    <img src="{{ Storage::url($companyInfo->logo) }}" alt="Logo" class="w-10 h-10 object-contain">
                                @else
                                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <div class="mb-10">
                            <h2 class="text-3xl font-extrabold text-white mb-2">Masuk ke Akun</h2>
                            <p class="text-slate-400">Isi detail Anda untuk melanjutkan</p>
                        </div>

                        <!-- Error Message -->
                        @if(session('error'))
                            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-red-400">{{ session('error') }}</span>
                            </div>
                        @endif

                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-start gap-3">
                                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-emerald-400">{{ session('success') }}</span>
                            </div>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('admin.login') }}"
                            @submit="isLoading = true"
                            class="space-y-5"
                        >
                            @csrf

                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-semibold text-slate-300 ml-1">Email</label>
                                <div class="input-group rounded-2xl flex items-center px-5">
                                    <svg class="w-5 h-5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        class="w-full bg-transparent border-0 py-4 px-4 text-slate-100 placeholder-slate-500 focus:ring-0 text-sm font-medium"
                                        placeholder="nama@bprs.com"
                                    >
                                </div>
                                @error('email')
                                    <p class="mt-1 text-xs font-semibold text-red-400 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between px-1">
                                    <label for="password" class="text-sm font-semibold text-slate-300">Kata Sandi</label>
                                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">Lupa kata sandi?</a>
                                </div>
                                <div class="input-group rounded-2xl flex items-center px-5">
                                    <svg class="w-5 h-5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <input
                                        type="password"
                                        x-bind:type="showPassword ? 'text' : 'password'"
                                        id="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        class="w-full bg-transparent border-0 py-4 px-4 text-slate-100 placeholder-slate-500 focus:ring-0 text-sm font-medium"
                                        placeholder="••••••••••"
                                    >
                                    <button
                                        type="button"
                                        class="flex items-center justify-center text-slate-500 hover:text-slate-300 transition-colors"
                                        @click="showPassword = !showPassword"
                                    >
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Captcha -->
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-300 ml-1">Verifikasi Keamanan</label>
                                <div class="input-group rounded-2xl p-4 flex items-center justify-between gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400 mb-1">Hitung Jawaban</span>
                                        <span class="text-2xl font-black text-white tracking-tighter">{{ $captcha_question }}</span>
                                    </div>
                                    <div class="w-28">
                                        <input
                                            type="number"
                                            name="captcha_answer"
                                            required
                                            class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-xl text-center text-xl font-bold text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"
                                            placeholder="?"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Remember -->
                            <div class="flex items-center">
                                <label class="flex items-center cursor-pointer group" @click.prevent="remember = !remember">
                                    <div
                                        class="custom-checkbox flex items-center justify-center mr-3"
                                        :class="{ 'checked': remember }"
                                    >
                                        <svg x-show="remember" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        class="sr-only"
                                        :checked="remember"
                                        @change="remember = $event.target.checked"
                                    >
                                    <span class="text-sm font-semibold text-slate-400 group-hover:text-slate-200 transition-colors">Tetap masuk</span>
                                </label>
                            </div>

                            <!-- Submit -->
                            <button
                                type="submit"
                                class="btn-primary w-full py-4 rounded-2xl text-white font-extrabold text-base tracking-wide flex items-center justify-center gap-3 disabled:opacity-70 disabled:cursor-not-allowed"
                                :disabled="isLoading"
                            >
                                <svg x-show="isLoading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-cloak>
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="isLoading ? 'Memproses...' : 'Masuk ke Dashboard'">Masuk ke Dashboard</span>
                            </button>
                        </form>

                        <!-- Footer -->
                        <div class="mt-10 flex items-center justify-between">
                            <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-500 hover:text-emerald-400 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali ke Beranda
                            </a>
                            <span class="text-[10px] font-black uppercase tracking-[0.25em] text-slate-700">
                                IP: {{ request()->ip() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
