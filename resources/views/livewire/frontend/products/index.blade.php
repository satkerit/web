<div>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 py-16 md:py-20">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">Produk & Layanan</h1>
                <p class="text-lg text-white/90 max-w-2xl mx-auto">Temukan berbagai produk dan layanan perbankan syariah yang sesuai dengan kebutuhan Anda</p>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search & Filter -->
            <div class="mb-8 bg-white rounded-xl shadow-sm p-4 md:p-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk berdasarkan nama..." class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                    <div class="w-full md:w-56">
                        <select wire:model.live="type" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                            <option value="">Semua Tipe</option>
                            <option value="simpanan_syariah">Simpanan Syariah</option>
                            <option value="pembiayaan_syariah">Pembiayaan Syariah</option>
                            <option value="deposito_syariah">Deposito Syariah</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters -->
                @if($search || $type)
                <div class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                    <span class="text-sm text-gray-500">Filter aktif:</span>
                    @if($search)
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 text-sm rounded-full">
                            "{{ $search }}"
                            <button wire:click="$set('search', '')" class="hover:text-emerald-900">&times;</button>
                        </span>
                    @endif
                    @if($type)
                        @php
                            $typeLabels = [
                                'simpanan_syariah' => 'Simpanan Syariah',
                                'pembiayaan_syariah' => 'Pembiayaan Syariah',
                                'deposito_syariah' => 'Deposito Syariah',
                            ];
                        @endphp
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 text-sm rounded-full">
                            {{ $typeLabels[$type] ?? $type }}
                            <button wire:click="$set('type', '')" class="hover:text-emerald-900">&times;</button>
                        </span>
                    @endif
                    <button wire:click="$set('search', ''); $set('type', '')" class="text-sm text-gray-500 hover:text-gray-700 underline">
                        Reset semua
                    </button>
                </div>
                @endif
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 group">
                            @if($product->image)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ \App\Helpers\StorageHelper::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @else
                                <div class="aspect-video bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="p-5">
                                @php
                                    $typeLabels = [
                                        'simpanan_syariah' => 'Simpanan Syariah',
                                        'pembiayaan_syariah' => 'Pembiayaan Syariah',
                                        'deposito_syariah' => 'Deposito Syariah',
                                    ];
                                    $typeColors = [
                                        'simpanan_syariah' => 'bg-blue-100 text-blue-700',
                                        'pembiayaan_syariah' => 'bg-purple-100 text-purple-700',
                                        'deposito_syariah' => 'bg-amber-100 text-amber-700',
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $typeColors[$product->type] ?? 'bg-gray-100 text-gray-700' }} mb-3">
                                    {{ $typeLabels[$product->type] ?? $product->type }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition-colors">{{ $product->name }}</h3>
                                @if($product->short_description)
                                    <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $product->short_description }}</p>
                                @endif
                                <button wire:click="selectProduct({{ $product->id }})" class="inline-flex items-center gap-2 text-emerald-600 font-semibold text-sm hover:text-emerald-700 transition-colors">
                                    Lihat Detail
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-xl">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-600">
                        @if($search || $type)
                            Tidak ada produk yang sesuai dengan filter Anda.
                            <button wire:click="$set('search', ''); $set('type', '')" class="text-emerald-600 hover:underline">Reset filter</button>
                        @else
                            Belum ada produk yang tersedia saat ini.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </section>

    <!-- Product Detail Modal -->
    @if($showModal && $selectedProduct)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white">
                    @if($selectedProduct->image)
                        <div class="aspect-video">
                            <img src="{{ \App\Helpers\StorageHelper::url($selectedProduct->image) }}" alt="{{ $selectedProduct->name }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="p-6">
                        @php
                            $typeLabels = [
                                'simpanan_syariah' => 'Simpanan Syariah',
                                'pembiayaan_syariah' => 'Pembiayaan Syariah',
                                'deposito_syariah' => 'Deposito Syariah',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 mb-3">
                            {{ $typeLabels[$selectedProduct->type] ?? $selectedProduct->type }}
                        </span>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $selectedProduct->name }}</h3>
                        @if($selectedProduct->short_description)
                            <p class="text-gray-600 mb-4">{{ $selectedProduct->short_description }}</p>
                        @endif
                        @if($selectedProduct->description)
                            <div class="prose prose-sm max-w-none text-gray-600">
                                {!! $selectedProduct->description !!}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                    <button wire:click="closeModal" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
