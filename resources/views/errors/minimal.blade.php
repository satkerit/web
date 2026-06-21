<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'BPRS Bangka Belitung') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .gradient-bg {
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 50%, #0d9488 100%);
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }

        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .error-code {
            font-size: clamp(8rem, 20vw, 14rem);
            line-height: 1;
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.5;
            transition: transform 0.3s ease;
        }

        .btn-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-hover:hover {
            transform: translateY(-2px);
        }

        .btn-hover:active {
            transform: translateY(0);
        }

        .sparkle {
            position: fixed;
            pointer-events: none;
            border-radius: 50%;
            background: white;
            opacity: 0.8;
            animation: sparkle-fade 1s ease-out forwards;
        }

        @keyframes sparkle-fade {
            0% { transform: scale(0) rotate(0deg); opacity: 0.8; }
            50% { transform: scale(1.5) rotate(180deg); opacity: 0.6; }
            100% { transform: scale(0) rotate(360deg); opacity: 0; }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-teal-50/30 flex items-center justify-center p-4 overflow-hidden" id="error-body">
    <!-- Decorative Elements -->
    <div class="decoration-circle w-96 h-96 bg-teal-300/30 -top-48 -left-48 fixed" id="circle1"></div>
    <div class="decoration-circle w-80 h-80 bg-emerald-300/30 -bottom-40 -right-40 fixed" id="circle2"></div>
    <div class="decoration-circle w-64 h-64 bg-cyan-300/20 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 fixed" id="circle3"></div>

    <div class="relative z-10 max-w-2xl w-full text-center">
        <!-- Error Code -->
        <div class="slide-up">
            <h1 class="error-code font-extrabold tracking-tight float-animation" id="error-code">
                @yield('code')
            </h1>
        </div>

        <!-- Content Card -->
        <div class="glass-card rounded-3xl shadow-2xl shadow-gray-200/50 p-8 md:p-12 -mt-8 slide-up" style="animation-delay: 0.1s;">
            <!-- Icon -->
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl flex items-center justify-center @yield('icon-bg', 'bg-gradient-to-br from-teal-500 to-emerald-500') shadow-lg @yield('icon-shadow', 'shadow-teal-500/30') btn-hover cursor-pointer" id="icon-card" onclick="toggleIconAnimation()">
                @yield('icon')
            </div>

            <!-- Title -->
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                @yield('title')
            </h2>

            <!-- Message -->
            <p class="text-gray-500 text-lg mb-8 max-w-md mx-auto">
                @yield('message')
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3.5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-semibold rounded-xl shadow-lg shadow-teal-500/30 btn-hover hover:shadow-teal-500/50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Kembali ke Beranda
                </a>
                <button onclick="goBack()" class="inline-flex items-center justify-center px-6 py-3.5 bg-white text-gray-700 font-semibold rounded-xl border-2 border-gray-200 hover:border-teal-300 hover:text-teal-600 btn-hover">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Halaman Sebelumnya
                </button>
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-8 text-gray-400 text-sm slide-up" style="animation-delay: 0.2s;">
            {{ config('app.name', 'BPRS Bangka Belitung') }} &copy; {{ date('Y') }}
        </p>
    </div>

    <script>
        // Interactive: Parallax effect on mouse move
        document.addEventListener('mousemove', function(e) {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;

            const circle1 = document.getElementById('circle1');
            const circle2 = document.getElementById('circle2');
            const circle3 = document.getElementById('circle3');

            if (circle1) circle1.style.transform = `translate(${x * 20}px, ${y * 20}px)`;
            if (circle2) circle2.style.transform = `translate(${-x * 15}px, ${-y * 15}px)`;
            if (circle3) circle3.style.transform = `translate(-50% + ${x * 10}px, -50% + ${y * 10}px)`;
        });

        // Interactive: Create sparkles on click
        document.addEventListener('click', function(e) {
            for (let i = 0; i < 5; i++) {
                createSparkle(e.clientX, e.clientY);
            }
        });

        function createSparkle(x, y) {
            const sparkle = document.createElement('div');
            sparkle.className = 'sparkle';
            sparkle.style.left = (x + Math.random() * 40 - 20) + 'px';
            sparkle.style.top = (y + Math.random() * 40 - 20) + 'px';
            sparkle.style.width = (Math.random() * 8 + 4) + 'px';
            sparkle.style.height = sparkle.style.width;
            document.body.appendChild(sparkle);

            setTimeout(() => sparkle.remove(), 1000);
        }

        // Interactive: Toggle icon animation
        function toggleIconAnimation() {
            const iconCard = document.getElementById('icon-card');
            if (iconCard) {
                iconCard.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    iconCard.style.transform = 'scale(1)';
                }, 150);
            }
        }

        // Go back with fallback
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '{{ url('/') }}';
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.key === 'Backspace') {
                goBack();
            }
            if (e.key === 'h' || e.key === 'H') {
                window.location.href = '{{ url('/') }}';
            }
        });
    </script>
</body>
</html>
