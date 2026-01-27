<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Lelang Agunan - ' . config('app.name', 'BPRS Bangka Belitung') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800|inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js', 'resources/js/admin.js'])
    @livewireStyles

    <style>
        /* Admin Auction Specific Styling */
        :root {
            --auction-admin-primary: #d97706;
            --auction-admin-secondary: #ea580c;
            --auction-admin-accent: #f59e0b;
            --auction-admin-success: #059669;
            --auction-admin-warning: #dc2626;
            --auction-admin-info: #0284c7;
            --auction-admin-dark: #1f2937;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 25%, #fed7aa 75%, #fdba74 100%);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }

        /* Admin Auction Header */
        .admin-auction-header {
            background: linear-gradient(135deg, #d97706, #ea580c);
            color: white;
            box-shadow: 0 4px 20px rgba(217, 119, 6, 0.3);
        }

        /* Admin Auction Sidebar */
        .admin-auction-sidebar {
            background: linear-gradient(180deg, #1f2937 0%, #374151 100%);
            border-right: 3px solid #d97706;
        }

        .admin-auction-sidebar .nav-item {
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 4px 8px;
        }

        .admin-auction-sidebar .nav-item:hover {
            background: rgba(217, 119, 6, 0.2);
            transform: translateX(4px);
        }

        .admin-auction-sidebar .nav-item.active {
            background: linear-gradient(135deg, #d97706, #ea580c);
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.4);
        }

        /* Admin Auction Cards */
        .admin-auction-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(217, 119, 6, 0.1);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(217, 119, 6, 0.1);
            transition: all 0.3s ease;
        }

        .admin-auction-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(217, 119, 6, 0.2);
            border-color: rgba(217, 119, 6, 0.3);
        }

        /* Admin Auction Buttons */
        .btn-auction-admin-primary {
            background: linear-gradient(135deg, #d97706, #ea580c);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-auction-admin-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.4);
            background: linear-gradient(135deg, #ea580c, #dc2626);
        }

        .btn-auction-admin-secondary {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-auction-admin-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.4);
        }

        .btn-auction-admin-success {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-auction-admin-danger {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
            border: none;
        }

        /* Admin Auction Form Elements */
        .admin-auction-input {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .admin-auction-input:focus {
            border-color: #d97706;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
            background: white;
        }

        /* Admin Auction Table */
        .admin-auction-table {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(217, 119, 6, 0.1);
        }

        .admin-auction-table th {
            background: linear-gradient(135deg, #f9fafb, #f3f4f6);
            color: #374151;
            font-weight: 600;
            padding: 16px;
            border-bottom: 2px solid #d97706;
        }

        .admin-auction-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(217, 119, 6, 0.1);
        }

        .admin-auction-table tr:hover {
            background: rgba(217, 119, 6, 0.05);
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-draft { background: #f3f4f6; color: #6b7280; }
        .status-published { background: #dbeafe; color: #1d4ed8; }
        .status-registration_open { background: #d1fae5; color: #059669; }
        .status-auction_scheduled { background: #fef3c7; color: #d97706; }
        .status-sold { background: #dcfce7; color: #16a34a; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }

        /* Animations */
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-in-right { animation: slideInRight 0.5s ease-out; }
        .animate-slide-in-left { animation: slideInLeft 0.5s ease-out; }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-auction-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-auction-sidebar.open {
                transform: translateX(0);
            }
        }

        /* Loading States */
        .loading-shimmer {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Custom Scrollbar */
        .admin-auction-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .admin-auction-scroll::-webkit-scrollbar-track {
            background: rgba(217, 119, 6, 0.1);
            border-radius: 4px;
        }

        .admin-auction-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #d97706, #ea580c);
            border-radius: 4px;
        }

        .admin-auction-scroll::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #ea580c, #dc2626);
        }
    </style>

    @stack('head')
</head>
<body class="font-sans antialiased admin-auction-scroll">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="admin-auction-sidebar w-64 min-h-screen flex-shrink-0" id="sidebar">
            <div class="p-6">
                <!-- Logo -->
                <div class="flex items-center space-x-3 mb-8">
                    @php $company = \App\Models\CompanyInfo::getInfo(); @endphp
                    @if($company?->logo)
                        <img src="{{ \App\Helpers\StorageHelper::url($company->logo) }}" 
                             alt="{{ $company->name }}" 
                             class="h-10 w-auto">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-lg">L</span>
                        </div>
                    @endif
                    <div>
                        <div class="text-white font-bold text-lg">Admin Lelang</div>
                        <div class="text-orange-300 text-sm">{{ $company->name ?? 'BPRS Babel' }}</div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('admin.auctions.index') }}" 
                       class="nav-item {{ request()->routeIs('admin.auctions.index') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 text-white hover:text-orange-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Daftar Lelang</span>
                    </a>

                    <a href="{{ route('admin.auctions.create') }}" 
                       class="nav-item {{ request()->routeIs('admin.auctions.create') ? 'active' : '' }} flex items-center space-x-3 px-4 py-3 text-white hover:text-orange-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Tambah Lelang</span>
                    </a>

                    <div class="border-t border-gray-600 my-4"></div>

                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-item flex items-center space-x-3 px-4 py-3 text-white hover:text-orange-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard Utama</span>
                    </a>

                    <a href="{{ route('auctions.index') }}" target="_blank"
                       class="nav-item flex items-center space-x-3 px-4 py-3 text-white hover:text-orange-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Lihat Frontend</span>
                    </a>
                </nav>
            </div>

            <!-- User Info -->
            <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-gray-600">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-white font-medium text-sm truncate">{{ auth()->user()->name }}</div>
                        <div class="text-orange-300 text-xs">Admin Lelang</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-orange-300 hover:text-white transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="admin-auction-header p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Mobile Menu Button -->
                        <button class="lg:hidden text-white" onclick="toggleSidebar()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $header ?? 'Admin Lelang Agunan' }}</h1>
                            <p class="text-orange-200 text-sm">{{ $subtitle ?? 'Kelola lelang agunan dengan mudah dan efisien' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Quick Stats -->
                        <div class="hidden md:flex items-center space-x-6 text-white">
                            <div class="text-center">
                                <div class="text-lg font-bold">{{ \App\Models\Auction::count() }}</div>
                                <div class="text-xs text-orange-200">Total Lelang</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold">{{ \App\Models\Auction::where('status', 'registration_open')->count() }}</div>
                                <div class="text-xs text-orange-200">Aktif</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold">{{ \App\Models\Auction::where('status', 'sold')->count() }}</div>
                                <div class="text-xs text-orange-200">Terjual</div>
                            </div>
                        </div>

                        <!-- Current Time -->
                        <div class="text-white text-sm">
                            <div id="current-time" class="font-medium"></div>
                            <div class="text-orange-200 text-xs">{{ now()->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-6 admin-auction-scroll">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white/80 backdrop-blur-sm border-t border-orange-200 p-4">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <div>
                        © {{ date('Y') }} {{ $company->name ?? 'BPRS Bangka Belitung' }}. Admin Lelang Agunan.
                    </div>
                    <div class="flex items-center space-x-4">
                        <span>Version 1.0</span>
                        <span>•</span>
                        <span>Laravel {{ app()->version() }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @livewireScripts

    <!-- Admin Auction JavaScript -->
    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-time').textContent = timeString;
        }

        // Update time every second
        setInterval(updateTime, 1000);
        updateTime();

        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnMenuButton = event.target.closest('button[onclick="toggleSidebar()"]');
            
            if (!isClickInsideSidebar && !isClickOnMenuButton && window.innerWidth <= 768) {
                sidebar.classList.remove('open');
            }
        });

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });

        // Form validation enhancement
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi.');
                }
            });
        });

        // Enhanced table interactions
        document.querySelectorAll('.admin-auction-table tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
                this.style.transition = 'transform 0.2s ease';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>

    @stack('scripts')
</body>
</html>