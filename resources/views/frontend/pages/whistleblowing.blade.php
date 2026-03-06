<x-frontend-layout>
    <x-slot name="title">Whistleblowing System - {{ $companyInfo->name ?? 'BPR Syariah' }}</x-slot>

    <!-- Hero -->
    <section class="relative pt-24 sm:pt-28 md:pt-32 pb-16 sm:pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.03&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-3 sm:px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-teal-100 text-xs sm:text-sm font-medium mb-4 sm:mb-6 animate-slide-up">
                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Sistem Pelaporan
            </span>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4 sm:mb-6 animate-slide-up delay-100 tracking-tight">Whistleblowing System</h1>
            <p class="text-base sm:text-lg md:text-xl text-white/80 max-w-2xl mx-auto animate-slide-up delay-200 px-4">Laporkan dugaan pelanggaran dengan aman dan terjamin kerahasiaannya</p>
        </div>
    </section>

    <!-- Ticket Search Section -->
    <section class="py-8 sm:py-12 -mt-10 relative z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-xl shadow-gray-200/50 border border-gray-100" x-intersect="$el.classList.add('animate-slide-up')">
                <div class="flex items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-teal-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/30 flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Lacak Laporan</h2>
                        <p class="text-gray-500 text-xs sm:text-sm">Masukkan nomor tiket untuk melihat status laporan Anda</p>
                    </div>
                </div>
                <livewire:frontend.ticket-search type="whistleblowing" />
            </div>
        </div>
    </section>

    <section class="py-12 sm:py-16 md:py-20 -mt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Info Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- About WBS -->
                    <div class="bg-white rounded-2xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100" x-intersect="$el.classList.add('animate-slide-in-left')">
                        <div class="w-14 h-14 bg-gradient-to-br from-rose-500 to-red-500 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-rose-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Tentang WBS</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Whistleblowing System adalah saluran pelaporan untuk menyampaikan informasi terkait dugaan pelanggaran yang terjadi di lingkungan {{ $companyInfo->name ?? 'BPR Syariah' }}.</p>
                    </div>

                    <!-- What to Report -->
                    <div class="bg-white rounded-2xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100" x-intersect="$el.classList.add('animate-slide-in-left')" style="animation-delay: 100ms">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Jenis Pelanggaran</h3>
                        <ul class="space-y-3">
                            @php
                                $violations = ['Kecurangan (Fraud)', 'Pelanggaran Peraturan', 'Pelanggaran Kode Etik', 'Penyalahgunaan Wewenang', 'Keselamatan Kerja'];
                            @endphp
                            @foreach($violations as $violation)
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </span>
                                <span class="text-gray-600 text-sm">{{ $violation }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Protection -->
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl p-6 text-white" x-intersect="$el.classList.add('animate-slide-in-left')" style="animation-delay: 200ms">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold mb-3">Perlindungan Pelapor</h3>
                        <p class="text-white/90 text-sm leading-relaxed">Kami menjamin kerahasiaan identitas pelapor dan memberikan perlindungan dari segala bentuk ancaman atau intimidasi.</p>
                    </div>

                    <!-- Contact -->
                    <div class="bg-white rounded-2xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100" x-intersect="$el.classList.add('animate-slide-in-left')" style="animation-delay: 300ms">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Kontak Alternatif</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $companyInfo->email_whistleblowing ?? $companyInfo->email ?? '-' }}</p>
                                </div>
                            </div>
                            @if($companyInfo->phone)
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Telepon</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $companyInfo->phone }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pengaduan Link -->
                    <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-200" x-intersect="$el.classList.add('animate-slide-in-left')" style="animation-delay: 400ms">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-emerald-800 mb-1">Pengaduan Layanan?</h4>
                                <p class="text-sm text-emerald-700 mb-3">Untuk menyampaikan keluhan terkait layanan atau produk, gunakan Pengaduan Nasabah.</p>
                                <a href="{{ route('pengaduan-nasabah') }}" class="inline-flex items-center text-sm font-medium text-emerald-700 hover:text-emerald-800">
                                    Ke Pengaduan Nasabah
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="lg:col-span-2" x-intersect="$el.classList.add('animate-slide-in-right')">
                    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-xl shadow-gray-200/50 border border-gray-100">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Form Pelaporan</h2>
                        <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8">Isi form di bawah ini untuk melaporkan dugaan pelanggaran</p>
                        <livewire:frontend.complaint.form />
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-frontend-layout>
