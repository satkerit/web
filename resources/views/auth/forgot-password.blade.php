<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/alpine-bundle.js'])

    @php $nonce = request()->attributes->get('csp_nonce'); @endphp

    <style nonce="{{ $nonce }}">
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }

        .login-card {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(209, 213, 219, 0.3);
        }

        .input-focus-ring:focus {
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) rgba(13, 148, 136, 0.15);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
            border-color: #0d9488;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-slate-900">
    @php
        $companyInfo = \App\Models\CompanyInfo::getInfo();
    @endphp

    <div class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8">
        <!-- Logo & Header -->
        <div class="mb-8 text-center animate-fade-in">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 bg-white rounded-2xl shadow-xl shadow-teal-500/10 p-2">
                @if($companyInfo && $companyInfo->logo)
                    <img src="{{ Storage::url($companyInfo->logo) }}" alt="Logo" class="w-full h-full object-contain">
                @else
                    <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                @endif
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                Reset Password
            </h1>
            <p class="mt-2 text-slate-500 font-medium">Masukkan email Anda untuk menerima instruksi reset</p>
        </div>

        <!-- Card -->
        <div class="w-full max-w-[440px] animate-slide-up">
            <div class="login-card rounded-3xl shadow-2xl p-8 sm:p-10">

                @if(session('status'))
                    <div class="mb-6 p-4 bg-teal-50 border-l-4 border-teal-500 rounded-r-xl flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-semibold text-teal-700 leading-tight">{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-bold text-slate-700 ml-1">Alamat Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-teal-600 transition-colors">
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
                                autofocus
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:bg-white input-focus-ring transition-all"
                                placeholder="nama@perusahaan.com"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs font-bold text-red-500 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Button -->
                    <button type="submit" class="btn-primary w-full py-4 rounded-2xl text-white font-bold text-base flex items-center justify-center gap-3 shadow-lg shadow-teal-600/20 active:scale-[0.98]">
                        <span>Kirim Link Reset</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

                <!-- Footer Links -->
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-teal-600 hover:text-teal-700 transition-colors group">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Animations -->
    <style nonce="{{ $nonce }}">
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .animate-slide-up { animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</body>
</html>
