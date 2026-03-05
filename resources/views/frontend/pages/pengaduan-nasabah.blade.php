<x-frontend-layout>
    <x-slot name="title">Pengaduan Nasabah - {{ $companyInfo->name ?? 'BPR Syariah' }}</x-slot>

    <!-- Hero -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.03&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-teal-100 text-sm font-medium mb-6 animate-slide-up">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Layanan Pengaduan
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 animate-slide-up delay-100 tracking-tight">Pengaduan Nasabah</h1>
            <p class="text-xl text-white/80 max-w-2xl mx-auto animate-slide-up delay-200">
                Kami berkomitmen untuk memberikan pelayanan terbaik. Sampaikan kritik, saran, atau pengaduan Anda untuk perbaikan layanan kami.
            </p>
        </div>
    </section>

    <!-- Ticket Search Section -->
    <section class="py-12 -mt-10 relative z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-xl shadow-gray-200/50 border border-gray-100" x-intersect="$el.classList.add('animate-slide-up')">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Lacak Pengaduan</h2>
                        <p class="text-gray-500 text-sm">Masukkan nomor tiket untuk melihat status pengaduan Anda</p>
                    </div>
                </div>
                <livewire:frontend.ticket-search type="customer" />
            </div>
        </div>
    </section>

    <section class="py-20 -mt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Info Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- About -->
                    <div class="bg-white rounded-2xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100" x-intersect="$el.classList.add('animate-slide-in-left')">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Tentang Layanan Ini</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Layanan pengaduan nasabah adalah saluran resmi untuk menyampaikan keluhan, saran, atau masukan terkait produk dan layanan {{ $companyInfo->name ?? 'BPR Syariah' }}.</p>
                    </div>

                    <!-- Categories -->
                    <div class="bg-white rounded-2xl p-6 shadow-xl shadow-gray-200/50 border border-gray-100" x-intersect="$el.classList.add('animate-slide-in-left')" style="animation-delay: 100ms">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Kategori Pengaduan</h3>
                        <ul class="space-y-3">
                            @php
                                $categories = ['Pelayanan', 'Produk', 'Transaksi', 'Fasilitas', 'Petugas/Karyawan'];
                            @endphp
                            @foreach($categories as $category)
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </span>
                                <span class="text-gray-600 text-sm">{{ $category }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- SLA Info -->
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl p-6 text-white" x-intersect="$el.classList.add('animate-slide-in-left')" style="animation-delay: 200ms">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold mb-3">Waktu Penyelesaian</h3>
                        <p class="text-white/90 text-sm leading-relaxed">Pengaduan akan ditindaklanjuti dalam waktu maksimal 20 hari kerja sesuai dengan ketentuan OJK.</p>
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
                                    <p class="text-sm font-medium text-gray-900">{{ $companyInfo->email_complaint ?? $companyInfo->email ?? '-' }}</p>
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

                    <!-- Whistleblowing Link -->
                    <div class="bg-amber-50 rounded-2xl p-6 border border-amber-200" x-intersect="$el.classList.add('animate-slide-in-left')" style="animation-delay: 400ms">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-amber-800 mb-1">Melaporkan Pelanggaran?</h4>
                                <p class="text-sm text-amber-700 mb-3">Untuk melaporkan dugaan kecurangan atau pelanggaran, gunakan Whistleblowing System.</p>
                                <a href="{{ route('whistleblowing') }}" class="inline-flex items-center text-sm font-medium text-amber-700 hover:text-amber-800">
                                    Ke Whistleblowing
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="lg:col-span-2" x-intersect="$el.classList.add('animate-slide-in-right')">
                    <div class="bg-white rounded-2xl p-8 shadow-xl shadow-gray-200/50 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Form Pengaduan</h2>
                        <p class="text-gray-600 mb-8">Isi form di bawah ini untuk menyampaikan pengaduan Anda</p>
                        <livewire:frontend.customer-complaint.form />
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-frontend-layout>
