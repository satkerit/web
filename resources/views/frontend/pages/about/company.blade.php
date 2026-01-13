<x-frontend-layout>
    <x-slot name="title">Profil Perusahaan - BPRS Bangka Belitung</x-slot>

    <!-- Hero -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-sm font-medium mb-6 animate-slide-up">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Tentang Kami
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 animate-slide-up delay-100">Profil Perusahaan</h1>
            <p class="text-xl text-white/80 max-w-2xl mx-auto animate-slide-up delay-200">Mengenal lebih dekat BPRS Bangka Belitung, bank perekonomian rakyat berbasis syariah yang terpercaya</p>
        </div>
    </section>

    @if($companyInfo)
    <!-- About Section -->
    <section class="py-20 -mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100">
                <div class="grid lg:grid-cols-2">
                    <div class="p-8 lg:p-12" x-intersect="$el.classList.add('animate-slide-in-left')">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ $companyInfo->name ?? 'BPRS Bangka Belitung' }}</h2>
                        <div class="prose prose-lg prose-emerald max-w-none text-gray-600">
                            {!! nl2br(e($companyInfo->description ?? 'Deskripsi perusahaan belum tersedia.')) !!}
                        </div>
                        @if($companyInfo->established_year)
                        <div class="mt-8 flex items-center">
                            <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Berdiri Sejak</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $companyInfo->established_year }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="relative h-80 lg:h-auto" x-intersect="$el.classList.add('animate-slide-in-right')">
                        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop" alt="Building" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/50 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-primary-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Vision -->
                <div class="bg-white rounded-2xl p-8 shadow-xl shadow-gray-200/50 card-hover border border-gray-100" x-intersect="$el.classList.add('animate-scale-in')">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-primary-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Visi</h3>
                    <div class="prose prose-primary text-gray-600">
                        {!! nl2br(e($companyInfo->vision ?? 'Menjadi bank pembiayaan rakyat syariah yang terpercaya dan memberikan manfaat bagi umat.')) !!}
                    </div>
                </div>

                <!-- Mission -->
                <div class="bg-white rounded-2xl p-8 shadow-xl shadow-gray-200/50 card-hover border border-gray-100" x-intersect="$el.classList.add('animate-scale-in')" style="animation-delay: 100ms">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Misi</h3>
                    <div class="prose prose-primary text-gray-600">
                        {!! nl2br(e($companyInfo->mission ?? 'Memberikan layanan perbankan syariah yang profesional, amanah, dan memberikan nilai tambah bagi nasabah.')) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- History -->
    @if($companyInfo->history)
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" x-intersect="$el.classList.add('animate-slide-up')">
                <span class="inline-block px-4 py-2 bg-amber-100 text-amber-700 rounded-full text-sm font-semibold mb-4">Sejarah</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Perjalanan Kami</h2>
            </div>
            <div class="bg-gradient-to-br from-gray-50 to-primary-50/30 rounded-2xl p-8 lg:p-12" x-intersect="$el.classList.add('animate-scale-in')">
                <div class="prose prose-lg prose-primary max-w-none text-gray-600" style="text-align: justify;">
                    {!! nl2br(e($companyInfo->history)) !!}
                </div>
            </div>
        </div>
    </section>
    @endif
    @else
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <p class="text-gray-500 text-lg">Informasi perusahaan belum tersedia</p>
        </div>
    </section>
    @endif

    <!-- CTA -->
    <section class="py-20 bg-gradient-to-r from-primary-600 to-primary-800">
        <div class="max-w-4xl mx-auto px-4 text-center" x-intersect="$el.classList.add('animate-scale-in')">
            <h2 class="text-3xl font-bold text-white mb-6">Ingin Mengenal Kami Lebih Dekat?</h2>
            <p class="text-xl text-white/80 mb-8">Kunjungi kantor kami atau hubungi customer service untuk informasi lebih lanjut</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('about.offices') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-700 rounded-xl font-semibold shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Lokasi Kantor
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/30 text-white rounded-xl font-semibold hover:bg-white/10 transition-all duration-300">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>
</x-frontend-layout>
