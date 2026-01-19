<x-frontend-layout>
    <x-slot name="title">{{ $product->name }} - BPRS Bangka Belitung</x-slot>

    <!-- Hero -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-sm mb-4">
                <a href="{{ route('home') }}" class="text-white/70 hover:text-white">Beranda</a>
                <span class="mx-2 text-white/50">/</span>
                <a href="{{ $product->type === 'simpanan_syariah' ? route('products.simpanan-syariah') : ($product->type === 'deposito_syariah' ? route('products.deposito-syariah') : route('products.pembiayaan-syariah')) }}" class="text-white/70 hover:text-white">
                    {{ $product->type === 'simpanan_syariah' ? 'Simpanan Syariah' : ($product->type === 'deposito_syariah' ? 'Deposito Syariah' : 'Pembiayaan Syariah') }}
                </a>
                <span class="mx-2 text-white/50">/</span>
                <span class="text-white">{{ $product->name }}</span>
            </nav>
            <h1 class="text-4xl font-bold text-white">{{ $product->name }}</h1>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    @if($product->image)
                    <div class="w-full max-w-[800px] mx-auto mb-6">
                        <div class="relative w-full aspect-[4/3] sm:aspect-[4/3] md:aspect-[4/3] lg:aspect-[4/3] overflow-hidden rounded-xl shadow-lg">
                            <img src="{{ \App\Helpers\StorageHelper::url($product->image) }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Deskripsi</h2>
                        <div class="prose prose-green max-w-none">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>

                    @if($product->features && count($product->features) > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Fitur</h2>
                        <ul class="space-y-2">
                            @foreach($product->features as $feature)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-600">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($product->benefits && count($product->benefits) > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Keuntungan</h2>
                        <ul class="space-y-2">
                            @foreach($product->benefits as $benefit)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-600">{{ $benefit }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($product->requirements && count($product->requirements) > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Persyaratan</h2>
                        <ul class="space-y-2">
                            @foreach($product->requirements as $requirement)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-gray-600">{{ $requirement }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Produk</h3>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Jenis Produk</p>
                                <p class="font-medium text-gray-900">{{ $product->type === 'simpanan_syariah' ? 'Simpanan Syariah' : ($product->type === 'deposito' ? 'Deposito' : 'Pembiayaan Syariah') }}</p>
                            </div>

                            @if($product->interest_rate)
                            <div>
                                <p class="text-sm text-gray-500">Bagi Hasil</p>
                                <p class="font-medium text-green-600">{{ $product->interest_rate }}</p>
                            </div>
                            @endif
                        </div>

                        <hr class="my-6">

                        <div class="space-y-3">
                            <a href="{{ route('contact') }}" class="block w-full bg-green-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                                Hubungi Kami
                            </a>
                            <a href="{{ route('about.offices') }}" class="block w-full border border-green-600 text-green-600 text-center py-3 rounded-lg font-semibold hover:bg-green-50 transition">
                                Kunjungi Kantor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-frontend-layout>
