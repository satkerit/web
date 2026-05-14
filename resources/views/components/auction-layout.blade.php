<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Lelang Agunan - ' . config('app.name', 'BPRS Bangka Belitung') }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $metaDescription ?? 'Temukan berbagai lelang agunan terpercaya dengan harga terbaik di BPRS Bangka Belitung. Rumah, tanah, ruko, dan properti komersial lainnya.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'lelang agunan, lelang properti, BPRS Babel, auction, property auction, rumah lelang, tanah lelang' }}">
    <meta name="author" content="BPRS Bangka Belitung">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $title ?? 'Lelang Agunan - BPRS Bangka Belitung' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Temukan berbagai lelang agunan terpercaya dengan harga terbaik di BPRS Bangka Belitung.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(isset($ogImage))
    <meta property="og:image" content="{{ $ogImage }}">
    @endif

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Lelang Agunan - BPRS Bangka Belitung' }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'Temukan berbagai lelang agunan terpercaya dengan harga terbaik di BPRS Bangka Belitung.' }}">

    @php $company = \App\Models\CompanyInfo::getInfo(); @endphp
    @if($company?->favicon)
    <link rel="icon" href="{{ \App\Helpers\StorageHelper::url($company->favicon) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ \App\Helpers\StorageHelper::url($company->favicon) }}" type="image/x-icon">
    @endif

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800|inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/css/frontend-fixes.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Auction-specific styling */
        :root {
            --auction-primary: #d97706;
            --auction-secondary: #ea580c;
            --auction-accent: #f59e0b;
            --auction-success: #059669;
            --auction-warning: #dc2626;
            --auction-info: #0284c7;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%, #fed7aa 100%);
            min-height: 100vh;
        }
        
        h1, h2, h3, h4, h5, h6 { 
            font-family: 'Montserrat', sans-serif; 
            font-weight: 700;
        }

        /* Auction-specific animations */
        @keyframes auction-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
        }

        @keyframes auction-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(217, 119, 6, 0.3); }
            50% { box-shadow: 0 0 30px rgba(217, 119, 6, 0.6); }
        }

        @keyframes countdown-tick {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        @keyframes float-auction {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-10px) rotate(1deg); }
            66% { transform: translateY(-5px) rotate(-1deg); }
        }

        @keyframes slide-up-auction {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fade-in-auction {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scale-in-auction {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Auction-specific classes */
        .auction-pulse { animation: auction-pulse 2s ease-in-out infinite; }
        .auction-glow { animation: auction-glow 3s ease-in-out infinite; }
        .countdown-tick { animation: countdown-tick 1s ease-in-out; }
        .float-auction { animation: float-auction 4s ease-in-out infinite; }
        .slide-up-auction { animation: slide-up-auction 0.8s ease-out forwards; }
        .fade-in-auction { animation: fade-in-auction 1s ease-out forwards; }
        .scale-in-auction { animation: scale-in-auction 0.6s ease-out forwards; }

        /* Auction card styling */
        .auction-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(217, 119, 6, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .auction-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px -12px rgba(217, 119, 6, 0.25);
            border-color: rgba(217, 119, 6, 0.3);
        }

        /* Status badges */
        .status-active { 
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            animation: auction-pulse 2s ease-in-out infinite;
        }

        .status-upcoming { 
            background: linear-gradient(135deg, #d97706, #f59e0b);
            color: white;
        }

        .status-closed { 
            background: linear-gradient(135deg, #6b7280, #9ca3af);
            color: white;
        }

        .status-sold { 
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
        }

        /* Price styling */
        .auction-price {
            background: linear-gradient(135deg, #d97706, #ea580c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        /* Countdown timer */
        .countdown-timer {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            border-radius: 12px;
            padding: 8px 16px;
            font-weight: 600;
            animation: auction-glow 3s ease-in-out infinite;
        }

        /* Search form styling */
        .auction-search-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(217, 119, 6, 0.2);
            box-shadow: 0 20px 40px rgba(217, 119, 6, 0.1);
        }

        /* Button styling */
        .btn-auction-primary {
            background: linear-gradient(135deg, #d97706, #ea580c);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-auction-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(217, 119, 6, 0.4);
        }

        .btn-auction-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-auction-primary:hover::before {
            left: 100%;
        }

        /* Glassmorphism effects */
        .glass-auction {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-auction-dark {
            background: rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .auction-card {
                margin-bottom: 1rem;
            }
            
            .auction-search-form {
                padding: 1rem;
            }
        }

        /* Loading states */
        .auction-loading {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: loading-shimmer 2s infinite;
        }

        @keyframes loading-shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Auction-specific scrollbar */
        .auction-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .auction-scroll::-webkit-scrollbar-track {
            background: rgba(217, 119, 6, 0.1);
            border-radius: 4px;
        }

        .auction-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #d97706, #ea580c);
            border-radius: 4px;
        }

        .auction-scroll::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #ea580c, #dc2626);
        }

        /* Intersection observer animations */
        [x-intersect] {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        [x-intersect].intersected {
            opacity: 1;
            transform: translateY(0);
        }

        /* Performance optimizations */
        .auction-hero {
            will-change: transform;
            contain: layout style paint;
        }

        .auction-grid {
            contain: layout;
        }

        /* Print styles */
        @media print {
            .no-print { display: none !important; }
            .auction-card { break-inside: avoid; }
        }
    </style>

    @stack('head')
</head>
<body class="font-sans antialiased text-gray-800 auction-scroll">
    <!-- Auction Header -->
    <header class="fixed w-full top-0 z-50 transition-all duration-300 glass-auction">
        @include('frontend.partials.auction-navbar', ['company' => $company])
    </header>

    <!-- Main Auction Content -->
    <main class="pt-20">
        {{ $slot }}
    </main>

    <!-- Auction Footer -->
    @include('frontend.partials.auction-footer', ['company' => $company])

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-8 right-8 flex flex-col gap-3 z-40 no-print">
        <!-- Back to Top -->
        <button x-data="{ show: false }" 
                @scroll.window="show = window.scrollY > 500" 
                x-show="show" 
                x-transition:enter="transition ease-out duration-300" 
                x-transition:enter-start="opacity-0 translate-y-4 scale-75" 
                x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })" 
                class="w-14 h-14 btn-auction-primary rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110 auction-glow">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>

        <!-- Quick Search -->
        <button @click="document.querySelector('#auction-search')?.focus()" 
                class="w-14 h-14 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110"
                title="Quick Search">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>

        <!-- Contact Support -->
        <a href="tel:{{ $company->phone ?? '' }}" 
           class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110"
           title="Hubungi Kami">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
        </a>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-orange-200 border-t-orange-600 rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-gray-600 font-medium">Memuat data lelang...</p>
        </div>
    </div>

    @livewireScripts
    @vite(['resources/js/pagination-fix.js'])
    
    <!-- Auction-specific JavaScript -->
    <script nonce="{{ $nonce }}">
        // Intersection Observer for animations
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('intersected');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('[x-intersect]').forEach(el => {
                observer.observe(el);
            });

            // Countdown timers
            document.querySelectorAll('.countdown-timer').forEach(timer => {
                const endTime = timer.dataset.endTime;
                if (endTime) {
                    updateCountdown(timer, endTime);
                    setInterval(() => updateCountdown(timer, endTime), 1000);
                }
            });

            // Loading states
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    document.getElementById('loading-overlay').classList.remove('hidden');
                });
            });
        });

        function updateCountdown(element, endTime) {
            const now = new Date().getTime();
            const end = new Date(endTime).getTime();
            const distance = end - now;

            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                element.innerHTML = `${days}h ${hours}j ${minutes}m ${seconds}d`;
                element.classList.add('countdown-tick');
                setTimeout(() => element.classList.remove('countdown-tick'), 1000);
            } else {
                element.innerHTML = 'Berakhir';
                element.classList.add('status-closed');
            }
        }

        // Performance optimizations
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                // Lazy load non-critical resources
                console.log('Auction page loaded successfully');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>