<x-frontend-layout>
    <x-slot name="title">{{ $title }} - BPRS Bangka Belitung</x-slot>

    <!-- Hero Section -->
    <section class="relative pt-24 sm:pt-28 md:pt-32 pb-12 sm:pb-16 md:pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.03&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-xs sm:text-sm font-medium mb-4 sm:mb-6 ring-1 ring-white/20">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Produk & Layanan
            </span>
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-3 sm:mb-4 md:mb-6 leading-tight tracking-tight px-4">{{ $title }}</h1>
            <p class="text-base sm:text-lg md:text-xl text-emerald-50 max-w-2xl mx-auto leading-relaxed px-4">{{ $subtitle }}</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8 sm:py-12 md:py-16 lg:py-20 bg-gray-50 -mt-6 sm:-mt-8 md:-mt-10 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Product Navigation -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl shadow-gray-200/50 p-2 sm:p-2.5 mb-8 sm:mb-10 md:mb-12 border border-gray-100 max-w-4xl mx-auto overflow-x-auto">
                <div class="flex flex-nowrap sm:flex-wrap justify-start sm:justify-center gap-2 min-w-max sm:min-w-0">
                    <a href="{{ route('products.simpanan-syariah') }}" class="flex-shrink-0 px-4 sm:px-5 md:px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-all duration-300 whitespace-nowrap touch-manipulation active:scale-95 {{ request()->routeIs('products.simpanan-syariah') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30 ring-2 ring-emerald-600 ring-offset-2' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-emerald-600' }}">
                        Simpanan Syariah
                    </a>
                    <a href="{{ route('products.pembiayaan-syariah') }}" class="flex-shrink-0 px-4 sm:px-5 md:px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-all duration-300 whitespace-nowrap touch-manipulation active:scale-95 {{ request()->routeIs('products.pembiayaan-syariah') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30 ring-2 ring-emerald-600 ring-offset-2' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-emerald-600' }}">
                        Pembiayaan Syariah
                    </a>
                    <a href="{{ route('products.deposito-syariah') }}" class="flex-shrink-0 px-4 sm:px-5 md:px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-all duration-300 whitespace-nowrap touch-manipulation active:scale-95 {{ request()->routeIs('products.deposito-syariah') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30 ring-2 ring-emerald-600 ring-offset-2' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-emerald-600' }}">
                        Deposito Syariah
                    </a>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                @forelse($products as $index => $product)
                <div class="group bg-white rounded-xl sm:rounded-2xl overflow-hidden shadow-lg sm:shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-emerald-900/10 transition-all duration-300 hover:-translate-y-1 touch-manipulation" x-intersect="$el.classList.add('animate-scale-in')" style="animation-delay: {{ $index * 100 }}ms">
                    <div class="h-1 sm:h-1.5 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                        @if($product->image)
                        <img src="{{ \App\Helpers\StorageHelper::url($product->image) }}"
                             alt="{{ $product->name }}"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                             loading="lazy">
                        @else
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-emerald-400 via-emerald-500 to-teal-600 flex items-center justify-center">
                            <span class="text-white text-3xl sm:text-4xl opacity-20 transform -rotate-12 select-none font-bold">BPRS</span>
                            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-5 sm:p-6 md:p-8">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3 group-hover:text-emerald-600 transition-colors tracking-tight line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-gray-600 mb-4 sm:mb-6 line-clamp-3 text-sm leading-relaxed">{{ $product->short_description }}</p>

                        @if($product->features && count($product->features) > 0)
                        <div class="mb-4 sm:mb-6 pt-4 sm:pt-6 border-t border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 sm:mb-3">Fitur Utama</p>
                            <ul class="space-y-1.5 sm:space-y-2">
                                @foreach(array_slice($product->features, 0, 3) as $feature)
                                <li class="flex items-start text-xs sm:text-sm text-gray-600">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span class="leading-tight">{{ $feature }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <a href="{{ route('products.show', $product->slug) }}" class="inline-flex items-center text-sm sm:text-base text-emerald-600 font-bold group/link hover:text-emerald-700 transition-colors touch-manipulation active:scale-95">
                            Selengkapnya
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-1.5 sm:ml-2 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-12 sm:py-16 md:py-20 bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl shadow-gray-200/50 border border-gray-100">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 tracking-tight px-4">Belum Ada Produk</h3>
                    <p class="text-sm sm:text-base text-gray-500 px-4">Produk untuk kategori ini belum tersedia saat ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-r from-emerald-600 to-teal-700 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10" x-intersect="$el.classList.add('animate-scale-in')">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4 sm:mb-6 tracking-tight leading-tight px-4">Tertarik dengan Produk Kami?</h2>
            <p class="text-base sm:text-lg md:text-xl text-white/90 mb-6 sm:mb-8 md:mb-10 leading-relaxed px-4">Hubungi kami untuk informasi lebih lanjut atau kunjungi kantor terdekat untuk konsultasi personal.</p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-white text-emerald-700 rounded-xl font-bold shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 touch-manipulation active:scale-95 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Hubungi Kami
                </a>
                <a href="{{ route('about.offices') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border-2 border-white/30 text-white rounded-xl font-bold hover:bg-white/10 transition-all duration-300 backdrop-blur-sm tracking-tight touch-manipulation active:scale-95 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Lokasi Kantor
                </a>
            </div>
        </div>
    </section>
</x-frontend-layout>
