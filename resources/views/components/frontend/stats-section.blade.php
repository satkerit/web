@props(['companyInfo'])

<!-- Stats Section -->
@php
    $hasStats = $companyInfo && (
        ($companyInfo->stat_years_experience && $companyInfo->stat_years_experience > 0) ||
        ($companyInfo->stat_branch_offices && $companyInfo->stat_branch_offices > 0) ||
        ($companyInfo->stat_total_assets && $companyInfo->stat_total_assets !== 'N/A') ||
        ($companyInfo->stat_cash_offices && $companyInfo->stat_cash_offices > 0) ||
        ($companyInfo->stat_mobile_cash_offices && $companyInfo->stat_mobile_cash_offices > 0)
    );
@endphp
@if($hasStats)
<section class="relative py-20 bg-white overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-xl animate-float"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-teal-500/10 rounded-full blur-xl animate-float-delayed"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-blue-500/5 to-emerald-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex flex-wrap justify-center gap-6 lg:gap-10 stagger-container">
            <!-- Tahun Pengalaman -->
            @if($companyInfo->stat_years_experience && $companyInfo->stat_years_experience > 0)
            <div class="stats-card text-center group stagger-item fade-in-section w-36 sm:w-40">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br shadow-lg ring-4 ring-white/50 from-blue-500 to-indigo-500 rounded-full flex items-center justify-center mb-4 mx-auto group-hover:animate-pulse-glow transition-all duration-300 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stats-counter text-3xl font-bold text-gray-900 mb-2" data-target="{{ $companyInfo->stat_years_experience }}" data-suffix="+">0+</div>
                <div class="text-gray-600 text-sm">Tahun Pengalaman</div>
            </div>
            @endif

            <!-- Kantor Cabang -->
            @if($companyInfo->stat_branch_offices && $companyInfo->stat_branch_offices > 0)
            <div class="stats-card text-center group stagger-item fade-in-section delay-100 w-36 sm:w-40">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br shadow-lg ring-4 ring-white/50 from-emerald-500 to-teal-500 rounded-full flex items-center justify-center mb-4 mx-auto group-hover:animate-pulse-glow transition-all duration-300 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="stats-counter text-3xl font-bold text-gray-900 mb-2" data-target="{{ $companyInfo->stat_branch_offices }}" data-suffix="+">0+</div>
                <div class="text-gray-600 text-sm">Kantor Cabang</div>
            </div>
            @endif

            <!-- Total Aset -->
            @if($companyInfo->stat_total_assets && $companyInfo->stat_total_assets !== 'N/A')
            <div class="stats-card text-center group stagger-item fade-in-section delay-200 w-36 sm:w-40">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br shadow-lg ring-4 ring-white/50 from-amber-500 to-orange-500 rounded-full flex items-center justify-center mb-4 mx-auto group-hover:animate-pulse-glow transition-all duration-300 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">{{ $companyInfo->stat_total_assets }}</div>
                <div class="text-gray-600 text-sm">Total Aset</div>
            </div>
            @endif

            <!-- Kantor Kas -->
            @if($companyInfo->stat_cash_offices && $companyInfo->stat_cash_offices > 0)
            <div class="stats-card text-center group stagger-item fade-in-section delay-300 w-36 sm:w-40">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br shadow-lg ring-4 ring-white/50 from-rose-500 to-pink-500 rounded-full flex items-center justify-center mb-4 mx-auto group-hover:animate-pulse-glow transition-all duration-300 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
                <div class="stats-counter text-3xl font-bold text-gray-900 mb-2" data-target="{{ $companyInfo->stat_cash_offices }}" data-suffix="+">0+</div>
                <div class="text-gray-600 text-sm">Kantor Kas</div>
            </div>
            @endif

            <!-- Kantor Kas Keliling -->
            @if($companyInfo->stat_mobile_cash_offices && $companyInfo->stat_mobile_cash_offices > 0)
            <div class="stats-card text-center group stagger-item fade-in-section delay-400 w-36 sm:w-40">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br shadow-lg ring-4 ring-white/50 from-purple-500 to-indigo-500 rounded-full flex items-center justify-center mb-4 mx-auto group-hover:animate-pulse-glow transition-all duration-300 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <div class="stats-counter text-3xl font-bold text-gray-900 mb-2" data-target="{{ $companyInfo->stat_mobile_cash_offices }}" data-suffix="+">0+</div>
                <div class="text-gray-600 text-sm">Kantor Kas Keliling</div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif
