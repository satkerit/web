@props(['products'])

<!-- Products Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50/30 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Section Header -->
        <div class="text-center mb-16 fade-in-section">
            <div class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4 animate-bounce-in">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Produk & Layanan
            </div>
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-900 mb-4 animate-fade-in-up delay-200">Solusi Keuangan Syariah Terbaik</h2>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg animate-fade-in-up delay-300">Berbagai produk simpanan dan pembiayaan syariah yang dirancang untuk memenuhi kebutuhan finansial Anda</p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-container">
            @forelse($products as $index => $product)
            <div class="product-card bg-white rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 stagger-item scale-in" style="animation-delay: {{ $index * 0.1 }}s">
                <!-- Product Image -->
                <div class="relative aspect-[4/3] overflow-hidden">
                    @if($product->image)
                    <x-optimized-image
                         src="{{ \App\Helpers\StorageHelper::url($product->image) }}"
                         alt="{{ $product->name }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                         aspect-ratio="4/3"
                    />
                    @else
                    <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-emerald-400 via-emerald-500 to-teal-600 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    @endif
                    <span class="absolute top-3 left-3 sm:top-4 sm:left-4 px-2 py-1 sm:px-3 sm:py-1.5 text-xs font-semibold rounded-lg bg-primary-500 text-white shadow-lg">
                        {{ $product->type === 'simpanan_syariah' ? 'Simpanan' : ($product->type === 'deposito' ? 'Deposito' : 'Pembiayaan') }}
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="text-lg lg:text-xl font-bold tracking-tight text-gray-900 mb-3 line-clamp-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $product->short_description }}</p>
                    <a href="{{ route('products.show', $product->slug) }}" class="inline-flex items-center px-4 py-2 bg-primary-50 text-primary-600 rounded-full font-semibold hover:bg-primary-600 hover:text-white transition-all duration-300 group">
                        Selengkapnya
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20 fade-in-section">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-in">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <p class="text-gray-500 text-lg">Belum ada produk tersedia</p>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-12 fade-in-section">
            <a href="{{ route('products.simpanan-syariah') }}" class="btn-shine inline-flex items-center px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all hover:scale-105">
                Lihat Semua Produk
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
