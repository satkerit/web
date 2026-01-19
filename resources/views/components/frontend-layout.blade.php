<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'BPRS Bangka Belitung') }}</title>

    @php $company = \App\Models\CompanyInfo::getInfo(); @endphp
    @if($company?->favicon)
    <link rel="icon" href="{{ Storage::url($company->favicon) }}" type="image/x-icon">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/css/frontend-fixes.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Custom Animations */
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes pulse-slow { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
        @keyframes slide-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slide-in-left { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slide-in-right { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes scale-in { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        @keyframes gradient-x { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-pulse-slow { animation: pulse-slow 3s ease-in-out infinite; }
        .animate-slide-up { animation: slide-up 0.6s ease-out forwards; }
        .animate-slide-in-left { animation: slide-in-left 0.6s ease-out forwards; }
        .animate-slide-in-right { animation: slide-in-right 0.6s ease-out forwards; }
        .animate-scale-in { animation: scale-in 0.5s ease-out forwards; }
        .animate-gradient { background-size: 200% 200%; animation: gradient-x 3s ease infinite; }

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

    <!-- Footer -->
    @include('frontend.partials.footer', ['company' => $company])

    <!-- Back to Top Button -->
    <button x-data="{ show: false }" @scroll.window="show = window.scrollY > 500" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" @click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="fixed bottom-8 right-8 w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110 z-40">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
    </button>

    @livewireScripts
    @vite(['resources/js/pagination-fix.js'])
    @stack('scripts')
</body>
</html>
