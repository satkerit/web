<x-frontend-layout>
    <x-slot name="title">Struktur Organisasi - BPRS Bangka Belitung</x-slot>

    @php
        $companyInfo = \App\Models\CompanyInfo::getInfo();
    @endphp

    <!-- Hero -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-sm font-medium mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                </svg>
                Tentang Kami
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Struktur Organisasi</h1>
            <p class="text-xl text-white/80 max-w-2xl mx-auto">Struktur organisasi {{ $companyInfo?->name ?? 'BPRS Bangka Belitung' }}</p>
        </div>
    </section>

    <section class="py-16 -mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100">
                @if($companyInfo?->organization_structure)
                <!-- Organization Structure Image -->
                <div class="p-6 md:p-10">
                    <div class="relative rounded-2xl overflow-hidden bg-gray-50">
                            <img
                            src="{{ \App\Helpers\StorageHelper::url($companyInfo->organization_structure) }}"
                            alt="Struktur Organisasi {{ $companyInfo->name ?? 'BPRS Bangka Belitung' }}"
                            class="w-full h-auto"
                            loading="lazy"
                        >
                    </div>

                    <!-- Zoom/Fullscreen Button -->
                    <div class="mt-6 flex justify-center">
                        <a href="{{ \App\Helpers\StorageHelper::url($companyInfo->organization_structure) }}"
                           target="_blank"
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-semibold rounded-xl shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 hover:scale-105 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                            </svg>
                            Lihat Ukuran Penuh
                        </a>
                    </div>
                </div>
                @else
                <!-- Placeholder when no image -->
                <div class="p-10 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Struktur Organisasi</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Gambar struktur organisasi sedang dalam proses pembaruan. Silakan hubungi kami untuk informasi lebih lanjut.</p>

                    <div class="mt-8">
                        <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-semibold rounded-xl shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Additional Info -->
            <div class="mt-8 text-center">
                <p class="text-gray-500">
                    Untuk informasi lebih detail mengenai struktur organisasi, silakan
                    <a href="{{ route('contact') }}" class="text-teal-600 hover:text-teal-700 font-medium">hubungi kami</a>.
                </p>
            </div>
        </div>
    </section>
</x-frontend-layout>
