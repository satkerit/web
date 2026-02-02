<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">

    {{-- Idle Timeout Configuration --}}
    @auth
    <meta name="idle-timeout" content="{{ config('security.idle_timeout', 30) }}">
    <meta name="idle-warning" content="{{ config('session.idle_warning', 5) }}">
    <meta name="logout-url" content="{{ route('login') }}">
    <meta name="auto-extend" content="{{ config('session.auto_extend', 'true') }}">
    @endauth

    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>

    @php $company = \App\Models\CompanyInfo::getInfo(); @endphp
    @if($company?->favicon)
        <link rel="icon" href="{{ \App\Helpers\StorageHelper::url($company->favicon) }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ \App\Helpers\StorageHelper::url($company->favicon) }}" type="image/x-icon">
    @endif

    {{-- DNS Prefetch & Preconnect --}}
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Preload critical fonts --}}
    <link rel="preload" href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" as="style">

    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css', 'resources/js/alpine-components.js', 'resources/js/admin.js', 'resources/js/idle-timeout.js'])
    @livewireStyles
    @stack('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }

        * {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
        }

        /* Custom Scrollbar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Sidebar Background - Premium Deep & Glassy */
        .sidebar-bg {
            background: #0f172a; /* Slate 900 */
        }

        /* Responsive Table */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* Mobile card view for tables */
        @media (max-width: 767px) {
            .mobile-card-view table,
            .mobile-card-view thead,
            .mobile-card-view tbody,
            .mobile-card-view th,
            .mobile-card-view td,
            .mobile-card-view tr {
                display: block;
            }

            .mobile-card-view thead {
                display: none;
            }

            .mobile-card-view tr {
                margin-bottom: 1rem;
                border: 1px solid #e2e8f0;
                border-radius: 1rem;
                padding: 1.25rem;
                background: white;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }

            .mobile-card-view td {
                padding: 0.75rem 0;
                border: none;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #f1f5f9;
            }

            .mobile-card-view td:last-child {
                border-bottom: none;
            }

            .mobile-card-view td:before {
                content: attr(data-label);
                font-weight: 600;
                color: #64748b;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
        }
    </style>
</head>

<body class="bg-gray-50/50 antialiased overflow-x-hidden text-slate-600">

    <div x-data="adminLayout()" x-init="init()">

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" @click="closeSidebar()" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"
            x-cloak></div>

        {{-- Sidebar --}}
        <aside
            x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="sidebar-bg fixed inset-y-0 left-0 z-50 w-72 -translate-x-full lg:!translate-x-0 transform transition-transform duration-300 ease-in-out flex flex-col border-r border-slate-800 shadow-2xl">

            {{-- Logo --}}
            <div class="h-20 flex items-center justify-between px-6 border-b border-white/5 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5"
                    @click="closeSidebarOnMobile()">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/20 ring-1 ring-white/10">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-white font-bold text-lg leading-tight tracking-tight">Admin<span class="text-emerald-400">Panel</span></span>
                        <span class="text-slate-400 text-xs font-medium tracking-wide">Management System</span>
                    </div>
                </a>
                {{-- Close button for mobile --}}
                <button @click="closeSidebar()" aria-label="Close sidebar"
                    class="lg:hidden text-slate-400 hover:text-white p-2 rounded-lg hover:bg-white/5 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto sidebar-scroll px-4 py-6 space-y-1" @click="closeSidebarOnMobile()">
                @include('layouts.admin.menu')
            </nav>

            {{-- User Info --}}
            <div class="p-4 border-t border-white/5 flex-shrink-0 bg-slate-900/50">
                <div class="flex items-center gap-3.5 px-3 py-3 rounded-xl bg-white/5 hover:bg-white/10 transition-colors border border-white/5">
                    <div
                        class="w-10 h-10 rounded-lg bg-emerald-500/10 ring-1 ring-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                            <p class="text-xs text-slate-400 truncate">
                                {{ auth()->user()->getRoleDisplayName() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="min-h-screen lg:ml-72 transition-all duration-300">
            {{-- Header --}}
            <header class="sticky top-0 z-30 bg-white/70 backdrop-blur-xl border-b border-gray-200/50 supports-[backdrop-filter]:bg-white/60">
                <div class="h-20 px-4 sm:px-6 lg:px-8 flex items-center justify-between max-w-7xl mx-auto w-full">
                    <div class="flex items-center gap-4">
                        {{-- Hamburger menu for mobile --}}
                        <button @click="openSidebar()" aria-label="Open sidebar"
                            class="lg:hidden p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-xl font-bold text-slate-900 tracking-tight">@yield('title', 'Dashboard')</h1>
                        </div>
                    </div>

                    {{-- Header Actions --}}
                    <div class="flex items-center gap-3 sm:gap-4">
                        {{-- View Website --}}
                        <a href="{{ route('home') }}" target="_blank"
                            class="hidden sm:flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all group">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span>Website</span>
                        </a>

                        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

                        {{-- User Menu --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click.stop="open = !open"
                                class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500/50 group">
                                <div
                                    class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-emerald-500/20 group-hover:shadow-lg transition-all">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden md:block text-left mr-1">
                                    <p class="text-sm font-semibold text-slate-700 leading-none group-hover:text-slate-900 transition-colors">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ auth()->user()->getRoleDisplayName() }}</p>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors hidden sm:block" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" :class="{'rotate-180': open}">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                class="absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 py-2 z-50 ring-1 ring-slate-900/5">
                                <div class="px-4 py-3 border-b border-slate-50 bg-slate-50/50">
                                    <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="py-1.5">
                                    <a href="{{ route('admin.profile.edit') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profil Saya
                                    </a>
                                    <a href="{{ route('home') }}" target="_blank"
                                        class="sm:hidden flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Lihat Website
                                    </a>
                                </div>
                                <div class="border-t border-slate-100 pt-1.5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
                {{-- Success Alert --}}
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="mb-6 p-4 bg-emerald-50/80 backdrop-blur border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm sm:text-base text-emerald-900">Berhasil</h3>
                                <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button @click="show = false"
                            class="text-emerald-500 hover:text-emerald-700 p-2 rounded-xl hover:bg-emerald-100 transition-colors flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                {{-- Error Alert --}}
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show"
                        class="mb-6 p-4 bg-red-50/80 backdrop-blur border border-red-200 text-red-800 rounded-2xl flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm sm:text-base text-red-900">Terjadi Kesalahan</h3>
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button @click="show = false"
                            class="text-red-500 hover:text-red-700 p-2 rounded-xl hover:bg-red-100 transition-colors flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                <div class="animate-fade-in-up">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <style>
         @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
    </style>
    @livewireScripts
    @stack('scripts')
</body>

</html>
