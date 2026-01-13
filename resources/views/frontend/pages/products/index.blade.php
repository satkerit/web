<x-frontend-layout>
    <x-slot name="title">{{ $title }} - BPRS Bangka Belitung</x-slot>

    <!-- Hero -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-sm font-medium mb-6 animate-slide-up">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Produk & Layanan
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 animate-slide-up delay-100">{{ $title }}</h1>
            <p class="text-xl text-white/80 max-w-2xl mx-auto animate-slide-up delay-200">{{ $subtitle }}</p>
        </div>
    </section>

    <section class="py-20 -mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Product Navigation -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <a href="{{ route('products.simpanan-syariah') }}" class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('products.simpanan-syariah') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-white text-gray-700 hover:bg-emerald-50 shadow-md' }}">
                    Simpanan Syariah
                </a>
                <a href="{{ route('products.pembiayaan-syariah') }}" class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('products.pembiayaan-syariah') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-white text-gray-700 hover:bg-emerald-50 shadow-md' }}">
                    Pembiayaan Syariah
                </a>
                <a href="{{ route('products.deposito-syariah') }}" class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('products.deposito-syariah') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-white text-gray-700 hover:bg-emerald-50 shadow-md' }}">
                    Deposito Syariah
                </a>
                <a href="{{ route('products.kas-keliling') }}" class="px-6 py-3 rounded-xl font-semibold transition-all duration-300 {{ request()->routeIs('products.kas-keliling') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-white text-gray-700 hover:bg-emerald-50 shadow-md' }}">
                    Kas Keliling
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($products as $index => $product)
                <div class="group bg-white rounded-2xl overflow-hidden shadow-xl shadow-gray-200/50 card-hover border border-gray-100" x-intersect="$el.classList.add('animate-scale-in')" style="animation-delay: {{ $index * 100 }}ms">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-emerald-400 via-emerald-500 to-teal-600 flex items-center justify-center">
                            <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-emerald-600 transition-colors">{{ $product->name }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $product->short_description }}</p>

                        @if($product->features && count($product->features) > 0)
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Fitur Utama:</p>
                            <ul class="space-y-1">
                                @foreach(array_slice($product->features, 0, 3) as $feature)
                                <li class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $feature }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <a href="{{ route('products.show', $product->slug) }}" class="inline-flex items-center text-emerald-600 font-semibold group/link">
                            Selengkapnya
                            <svg class="w-5 h-5 ml-2 group-hover/link:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Produk</h3>
                    <p class="text-gray-500">Produk untuk kategori ini belum tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-gradient-to-r from-emerald-600 to-teal-600">
        <div class="max-w-4xl mx-auto px-4 text-center" x-intersect="$el.classList.add('animate-scale-in')">
            <h2 class="text-3xl font-bold text-white mb-6">Tertarik dengan Produk Kami?</h2>
            <p class="text-xl text-white/80 mb-8">Hubungi kami untuk informasi lebih lanjut atau kunjungi kantor terdekat</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-emerald-700 rounded-xl font-semibold shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Hubungi Kami
                </a>
                <a href="{{ route('about.offices') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/30 text-white rounded-xl font-semibold hover:bg-white/10 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Lokasi Kantor
                </a>
            </div>
        </div>
    </section>
</x-frontend-layout>
