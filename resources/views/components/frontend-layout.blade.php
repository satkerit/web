<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Permissions Policy for Geolocation -->
    <meta http-equiv="Permissions-Policy" content="geolocation=(self)">

    <title>{{ $title ?? config('app.name', 'BPRS Bangka Belitung') }}</title>

    @php
        $company = \App\Models\CompanyInfo::getInfo();
    @endphp
    @if($company?->favicon)
    <link rel="icon" href="{{ \App\Helpers\StorageHelper::url($company->favicon) }}" type="image/x-icon">
    @endif

    {{-- Performance Optimizations --}}
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">

    @if($company?->logo)
    <link rel="preload" as="image" href="{{ \App\Helpers\StorageHelper::url($company->logo) }}" fetchpriority="high">
    @endif

    <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800|inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/css/frontend-fixes.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Montserrat', sans-serif; }

        /* Custom Animations */
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes float-delayed { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
        @keyframes pulse-slow { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        @keyframes pulse-glow { 0%, 100% { opacity: 0.2; } 50% { opacity: 0.4; } }
        @keyframes bounce-in { 0% { opacity: 0; transform: scale(0.3); } 50% { opacity: 1; transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { transform: scale(1); } }
        @keyframes fade-in-section { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-in-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slide-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slide-in-left { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slide-in-right { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes scale-in { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        @keyframes gradient-x { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-float-delayed { animation: float-delayed 4s ease-in-out infinite; }
        .animate-pulse-slow { animation: pulse-slow 3s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
        .animate-bounce-in { animation: bounce-in 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        .animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; }
        .animate-slide-up { animation: slide-up 0.6s ease-out forwards; }
        .animate-slide-in-left { animation: slide-in-left 0.6s ease-out forwards; }
        .animate-slide-in-right { animation: slide-in-right 0.6s ease-out forwards; }
        .animate-scale-in { animation: scale-in 0.5s ease-out forwards; }
        .animate-gradient { background-size: 200% 200%; animation: gradient-x 3s ease infinite; }
        .fade-in-section { animation: fade-in-section 0.6s ease-out forwards; }
        .slide-in-left { animation: slide-in-left 0.8s ease-out forwards; }
        .slide-in-right { animation: slide-in-right 0.8s ease-out forwards; }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }

        /* Glassmorphism */
        .glass { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .glass-dark { background: rgba(0, 0, 0, 0.2); backdrop-filter: blur(10px); }

        /* Gradient Text */
        .gradient-text { background: linear-gradient(135deg, #059669, #10b981, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        /* Card Hover Effects */
        .card-hover { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); }

        /* Button Shine Effect */
        .btn-shine { position: relative; overflow: hidden; }
        .btn-shine::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); transition: left 0.5s; }
        .btn-shine:hover::before { left: 100%; }

        /* Scroll animations */
        [x-intersect] { opacity: 0; }

        /* Mobile Navigation Improvements */
        .mobile-nav-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        .mobile-nav-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .mobile-nav-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .mobile-nav-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 2px;
        }

        .mobile-nav-scroll::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.7);
        }

        /* Ensure mobile menu is above other elements */
        .mobile-nav-container {
            position: relative;
            z-index: 9999;
        }

        /* Hero Slider Performance Optimizations */
        .hero-slide-img {
            will-change: opacity;
            content-visibility: auto;
        }

        /* Reduce paint on transitions */
        [x-data] {
            contain: layout style paint;
        }

        /* Prayer Time Widget Styles */
        .prayer-widget-container {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .prayer-widget-container::-webkit-scrollbar {
            width: 3px;
        }

        .prayer-widget-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .prayer-widget-container::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .prayer-widget-container::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        /* Pulse animation for FAB */
        @keyframes ping {
            75%, 100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }

        .animate-ping {
            animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden;
        }

        /* Responsive adjustments for prayer widget */
        @media (max-width: 1279px) {
            .prayer-widget-container {
                max-width: 100vw;
            }
        }

        /* Ensure main content doesn't go under widget on large screens */
        @media (min-width: 1280px) {
            main {
                margin-right: 0;
            }
        }

        /* Alpine.js cloak */
        [x-cloak] {
            display: none !important;
        }
    </style>

    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">
    <!-- Header -->
    <header class="fixed w-full top-0 z-50 transition-all duration-300">
        @include('frontend.partials.navbar', ['company' => $company])
    </header>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Fixed Prayer Time Widget - Right Side -->
    <div x-data="{
        show: true,
        minimized: false,
        topPosition: 96,
        init() {
            console.log('Prayer widget sidebar initialized');
            if (window.innerWidth < 1024) {
                this.minimized = true;
            }
            this.calculateTopPosition();
            window.addEventListener('resize', () => {
                this.calculateTopPosition();
                this.minimized = window.innerWidth < 1024;
            });
        },
        calculateTopPosition() {
            const header = document.querySelector('header');
            if (header) {
                this.topPosition = header.offsetHeight + 16;
            }
        }
    }"
    class="fixed right-0 z-40 transition-all duration-300"
    :class="minimized ? 'translate-x-[calc(100%-3rem)]' : 'translate-x-0'"
    :style="'top: ' + topPosition + 'px; max-height: calc(100vh - ' + topPosition + 'px - 2rem);'">

        <!-- Toggle Button -->
        <button
            @click="minimized = !minimized"
            class="absolute left-0 top-4 -translate-x-full bg-gradient-to-r from-emerald-600 to-teal-700 text-white p-2 rounded-l-lg shadow-lg hover:shadow-xl transition-all z-10"
            :class="minimized ? 'opacity-100' : 'opacity-0 hover:opacity-100'"
            title="Toggle Prayer Times">
            <svg class="w-5 h-5 transition-transform duration-300" :class="minimized ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <!-- Close Button (Mobile) -->
        <button
            @click="show = false; $el.closest('[x-data]').style.display = 'none'"
            class="lg:hidden absolute right-2 top-2 bg-white/20 hover:bg-white/30 text-white p-1.5 rounded-lg transition-all z-10"
            title="Close">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Widget Container -->
        <div class="w-80 sm:w-96 overflow-y-auto prayer-widget-container shadow-2xl" style="max-height: inherit;">
            <x-prayer-time-widget />
        </div>
    </div>

    <!-- Footer -->
    @include('frontend.partials.footer', ['company' => $company])

    @livewireScripts
    @vite(['resources/js/pagination-fix.js'])
    @stack('scripts')
</body>
</html>
