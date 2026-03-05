<x-frontend-layout>
    <x-slot name="title">{{ $product->name }} - BPRS Bangka Belitung</x-slot>

    <!-- Hero -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.03&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-sm mb-6 animate-slide-up">
                <a href="{{ route('home') }}" class="text-emerald-100 hover:text-white transition-colors">Beranda</a>
                <span class="mx-2 text-emerald-100/50">/</span>
                <a href="{{ $product->type === 'simpanan_syariah' ? route('products.simpanan-syariah') : ($product->type === 'deposito_syariah' ? route('products.deposito-syariah') : route('products.pembiayaan-syariah')) }}" class="text-emerald-100 hover:text-white transition-colors">
                    {{ $product->type === 'simpanan_syariah' ? 'Simpanan Syariah' : ($product->type === 'deposito_syariah' ? 'Deposito Syariah' : 'Pembiayaan Syariah') }}
                </a>
                <span class="mx-2 text-emerald-100/50">/</span>
                <span class="text-white font-medium">{{ $product->name }}</span>
            </nav>
            <h1 class="text-4xl md:text-5xl font-bold text-white tracking-tight leading-tight animate-slide-up delay-100">{{ $product->name }}</h1>
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

                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6 md:p-8 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight mb-4 flex items-center">
                            <span class="w-1.5 h-8 bg-emerald-500 rounded-full mr-3"></span>
                            Deskripsi
                        </h2>
                        <div class="prose prose-emerald max-w-none text-gray-600 leading-relaxed">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>

                    @if($product->features && count($product->features) > 0)
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6 md:p-8 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight mb-6 flex items-center">
                            <span class="w-1.5 h-8 bg-emerald-500 rounded-full mr-3"></span>
                            Fitur Utama
                        </h2>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($product->features as $feature)
                            <li class="flex items-start p-3 bg-gray-50 rounded-xl hover:bg-emerald-50 transition-colors duration-300">
                                <div class="bg-white p-1.5 rounded-lg shadow-sm mr-3">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($product->benefits && count($product->benefits) > 0)
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6 md:p-8 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight mb-6 flex items-center">
                            <span class="w-1.5 h-8 bg-emerald-500 rounded-full mr-3"></span>
                            Keuntungan
                        </h2>
                        <ul class="space-y-3">
                            @foreach($product->benefits as $benefit)
                            <li class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 leading-relaxed">{{ $benefit }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($product->requirements && count($product->requirements) > 0)
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight mb-6 flex items-center">
                            <span class="w-1.5 h-8 bg-emerald-500 rounded-full mr-3"></span>
                            Persyaratan
                        </h2>
                        <ul class="space-y-3">
                            @foreach($product->requirements as $requirement)
                            <li class="flex items-start p-4 bg-blue-50/50 rounded-xl border border-blue-100/50">
                                <div class="bg-blue-100 p-1.5 rounded-lg mr-3 flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium self-center">{{ $requirement }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6 md:p-8 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 tracking-tight mb-6 pb-4 border-b border-gray-100">Informasi Produk</h3>

                        <div class="space-y-6">
                            <div>
                                <p class="text-sm text-gray-500 font-medium mb-1">Jenis Produk</p>
                                <p class="font-bold text-gray-900 text-lg">{{ $product->type === 'simpanan_syariah' ? 'Simpanan Syariah' : ($product->type === 'deposito' ? 'Deposito' : 'Pembiayaan Syariah') }}</p>
                            </div>

                            @if($product->interest_rate)
                            <div>
                                <p class="text-sm text-gray-500">Bagi Hasil</p>
                                <p class="font-medium text-emerald-600">{{ $product->interest_rate }}</p>
                            </div>
                            @endif
                        </div>

                        <hr class="my-6">

                        <div class="space-y-3">
                            @if($product->type === 'pembiayaan_syariah' && $product->brochure)
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($product->brochure) }}" target="_blank" class="block w-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-center py-3 rounded-xl font-semibold hover:bg-emerald-100 transition shadow-sm hover:shadow-md">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download Brosur
                                    </span>
                                </a>
                            @endif

                            <a href="{{ route('contact') }}" class="block w-full bg-emerald-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/40 transform hover:-translate-y-0.5">
                                Hubungi Kami
                            </a>
                            <a href="{{ route('about.offices') }}" class="block w-full border-2 border-emerald-600 text-emerald-600 text-center py-3 rounded-xl font-semibold hover:bg-emerald-50 transition hover:shadow-lg hover:shadow-emerald-600/10 transform hover:-translate-y-0.5">
                                Kunjungi Kantor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-frontend-layout>
