@php
    $company = $company ?? \App\Models\CompanyInfo::getInfo();
@endphp
<footer class="relative bg-gray-900 text-white overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative">
        <!-- Main Footer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 lg:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 lg:gap-12">
                <!-- Company Info -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <div class="mb-4 sm:mb-6">
                        <a href="{{ route('home') }}" class="inline-block transition-transform duration-300 hover:scale-105">
                            @if($company?->logo_footer)
                            @php
                                $opacity = ($company->logo_footer_opacity ?? 100) / 100;
                                $logoClasses = 'h-12 sm:h-14 lg:h-16 w-auto logo-no-bg';
                                if ($company->logo_footer_remove_bg) {
                                    $logoClasses .= ' logo-remove-bg';
                                }
                            @endphp
                            <img src="{{ \App\Helpers\StorageHelper::url($company->logo_footer) }}" alt="{{ $company->name }}" class="{{ $logoClasses }}" style="opacity: {{ $opacity }};">
                            @elseif($company?->logo)
                            <img src="{{ \App\Helpers\StorageHelper::url($company->logo) }}" alt="{{ $company->name }}" class="h-12 sm:h-14 lg:h-16 w-auto logo-no-bg">
                            @else
                            <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-xl sm:text-2xl">B</span>
                            </div>
                            @endif
                        </a>
                    </div>
                    <p class="text-gray-400 mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base">
                        {{ $company?->footer_description ?? $company?->description ?? 'Melayani dengan prinsip syariah untuk kesejahteraan umat.' }}
                    </p>
                    <div class="flex space-x-2 sm:space-x-3">
                        @if($company?->facebook)
                        <a href="{{ $company->facebook }}" target="_blank" aria-label="Facebook" class="w-9 h-9 sm:w-10 sm:h-10 bg-white/10 hover:bg-emerald-500 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        @endif
                        @if($company?->instagram)
                        <a href="{{ $company->instagram }}" target="_blank" aria-label="Instagram" class="w-9 h-9 sm:w-10 sm:h-10 bg-white/10 hover:bg-emerald-500 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        @endif
                        @if($company?->twitter)
                        <a href="{{ $company->twitter }}" target="_blank" aria-label="Twitter" class="w-9 h-9 sm:w-10 sm:h-10 bg-white/10 hover:bg-emerald-500 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        @endif
                        @if($company?->youtube)
                        <a href="{{ $company->youtube }}" target="_blank" aria-label="YouTube" class="w-9 h-9 sm:w-10 sm:h-10 bg-white/10 hover:bg-emerald-500 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-base sm:text-lg font-bold tracking-tight mb-4 sm:mb-6 flex items-center">
                        <span class="w-6 sm:w-8 h-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded mr-2 sm:mr-3"></span>
                        Tautan Cepat
                    </h4>
                    <ul class="space-y-2 sm:space-y-3">
                        <li><a href="{{ route('about.company') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Tentang Kami</a></li>
                        <li><a href="{{ route('products.simpanan-syariah') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Produk Simpanan</a></li>
                        <li><a href="{{ route('products.pembiayaan-syariah') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Produk Pembiayaan</a></li>
                        <li><a href="{{ route('products.deposito-syariah') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Deposito Syariah</a></li>
                        <li><a href="{{ route('news.index') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Berita & Artikel</a></li>
                        <li><a href="{{ route('careers.index') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Karir</a></li>
                    </ul>
                </div>

                <!-- Informasi -->
                <div>
                    <h4 class="text-base sm:text-lg font-semibold mb-4 sm:mb-6 flex items-center tracking-tight">
                        <span class="w-6 sm:w-8 h-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded mr-2 sm:mr-3"></span>
                        Informasi
                    </h4>
                    <ul class="space-y-2 sm:space-y-3">
                        <li><a href="{{ route('reports.keuangan-publikasi') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Laporan Keuangan</a></li>
                        <li><a href="{{ route('reports.tahunan') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Laporan Tahunan</a></li>
                        <li><a href="{{ route('pengaduan-nasabah') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Pengaduan Nasabah</a></li>
                        <li><a href="{{ route('whistleblowing') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Whistleblowing</a></li>
                        <li><a href="{{ route('download-logo') }}" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center group text-sm sm:text-base"><svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Download Logo</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-base sm:text-lg font-bold tracking-tight mb-4 sm:mb-6 flex items-center">
                        <span class="w-6 sm:w-8 h-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded mr-2 sm:mr-3"></span>
                        Hubungi Kami
                    </h4>
                    <ul class="space-y-3 sm:space-y-4">
                        @if($company?->address)
                        <li class="flex items-start">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="text-gray-400 text-sm sm:text-base">{!! nl2br(e($company->address)) !!}</span>
                        </li>
                        @endif
                        @if($company?->phone)
                        <li class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <span class="text-gray-400 text-sm sm:text-base">{{ $company->phone }}</span>
                        </li>
                        @endif
                        @if($company?->email)
                        <li class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-gray-400 text-sm sm:text-base break-all">{{ $company->email }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <p class="text-gray-400 text-xs sm:text-sm text-center sm:text-left">
                        © {{ date('Y') }} {{ $company?->name ?? 'BPRS Bangka Belitung' }}. All rights reserved.
                    </p>
                    <div class="flex items-center space-x-3 sm:space-x-6 text-xs sm:text-sm">
                        @if($company?->ojk_tagline)
                        <span class="text-gray-400">{{ $company->ojk_tagline }}</span>
                        @else
                        <span class="text-gray-500">Diawasi oleh OJK</span>
                        @endif
                        <span class="text-gray-600">|</span>
                        @if($company?->lps_tagline)
                        <span class="text-gray-400">{{ $company->lps_tagline }}</span>
                        @else
                        <span class="text-gray-500">Dijamin LPS</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button
        id="backToTop"
        onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-6 right-6 z-50 w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-full shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-300 opacity-0 invisible flex items-center justify-center group"
        aria-label="Kembali ke atas"
    >
        <svg class="w-5 h-5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    <script>
        // Back to Top Button Visibility
        (function() {
            const backToTopBtn = document.getElementById('backToTop');

            function toggleBackToTop() {
                if (window.scrollY > 300) {
                    backToTopBtn.classList.remove('opacity-0', 'invisible');
                    backToTopBtn.classList.add('opacity-100', 'visible');
                } else {
                    backToTopBtn.classList.add('opacity-0', 'invisible');
                    backToTopBtn.classList.remove('opacity-100', 'visible');
                }
            }

            window.addEventListener('scroll', toggleBackToTop);
            toggleBackToTop(); // Initial check
        })();
    </script>
</footer>
