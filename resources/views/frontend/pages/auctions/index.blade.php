<x-frontend-layout>
    <x-slot name="title">Lelang Properti - {{ config('app.name') }}</x-slot>
    <x-slot name="meta_description">Temukan berbagai lelang properti terpercaya dengan harga terbaik. Rumah, tanah, ruko, dan properti komersial lainnya.</x-slot>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Lelang Properti</h1>
                <p class="text-xl mb-8">Temukan properti impian Anda dengan harga terbaik melalui lelang terpercaya</p>
                
                <!-- Search Form -->
                <div class="max-w-4xl mx-auto">
                    <form method="GET" class="bg-white rounded-lg p-6 shadow-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Cari properti, lokasi..."
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
                            </div>
                            <div>
                                <select name="asset_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
                                    <option value="">Semua Jenis</option>
                                    @foreach($assetTypes as $value => $label)
                                        <option value="{{ $value }}" {{ request('asset_type') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
                                    <option value="">Semua Kota</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                                    Cari Lelang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Filters & Sort -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="asset_type" value="{{ request('asset_type') }}">
                        <input type="hidden" name="city" value="{{ request('city') }}">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Min</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                   placeholder="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Max</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                   placeholder="Unlimited" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="registration_open" {{ request('status') === 'registration_open' ? 'selected' : '' }}>Pendaftaran Dibuka</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasi</option>
                                <option value="auction_scheduled" {{ request('status') === 'auction_scheduled' ? 'selected' : '' }}>Terjadwal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                            <select name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                                <option value="date" {{ request('sort_by') === 'date' ? 'selected' : '' }}>Tanggal Lelang</option>
                                <option value="price" {{ request('sort_by') === 'price' ? 'selected' : '' }}>Harga</option>
                                <option value="featured" {{ request('sort_by') === 'featured' ? 'selected' : '' }}>Featured</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Results Info -->
                <div class="flex justify-between items-center mb-6">
                    <div class="text-gray-600">
                        Menampilkan {{ $auctions->firstItem() ?? 0 }}-{{ $auctions->lastItem() ?? 0 }} dari {{ $auctions->total() }} lelang
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('auctions.index', array_merge(request()->query(), ['sort_order' => 'asc'])) }}" 
                           class="px-3 py-1 text-sm border rounded {{ request('sort_order') === 'asc' ? 'bg-blue-100 border-blue-300' : 'border-gray-300' }}">
                            Ascending
                        </a>
                        <a href="{{ route('auctions.index', array_merge(request()->query(), ['sort_order' => 'desc'])) }}" 
                           class="px-3 py-1 text-sm border rounded {{ request('sort_order') === 'desc' ? 'bg-blue-100 border-blue-300' : 'border-gray-300' }}">
                            Descending
                        </a>
                    </div>
                </div>

                <!-- Auction Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($auctions as $auction)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                            <!-- Image -->
                            <div class="relative">
                                @if($auction->main_image)
                                    <img src="{{ $auction->main_image }}" alt="{{ $auction->title }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex flex-col space-y-1">
                                    @if($auction->is_featured)
                                        <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            Featured
                                        </span>
                                    @endif
                                    @if($auction->is_urgent)
                                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            Urgent
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Status -->
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $auction->status_color['bg'] }} {{ $auction->status_color['text'] }}">
                                        {{ $auction->status_label }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <div class="mb-2">
                                    <span class="text-sm text-blue-600 font-medium">{{ $auction->asset_type_label }}</span>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <a href="{{ route('auctions.show', $auction) }}" class="hover:text-blue-600 transition duration-300">
                                        {{ $auction->title }}
                                    </a>
                                </h3>
                                
                                <div class="text-sm text-gray-600 mb-3 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $auction->city }}
                                </div>

                                <div class="mb-4">
                                    <div class="text-2xl font-bold text-green-600">{{ $auction->formatted_limit_price }}</div>
                                    @if($auction->estimated_price)
                                        <div class="text-sm text-gray-500">Taksiran: {{ $auction->formatted_estimated_price }}</div>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        @if($auction->auction_date)
                                            {{ $auction->auction_date->format('d M Y') }}
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @if($auction->auction_date)
                                            {{ $auction->auction_date->format('H:i') }} WIB
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>

                                @if($auction->days_until_auction >= 0)
                                    <div class="text-sm text-orange-600 font-medium mb-4">
                                        {{ $auction->time_until_auction }}
                                    </div>
                                @endif

                                <div class="flex space-x-2">
                                    <a href="{{ route('auctions.show', $auction) }}" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md transition duration-300">
                                        Lihat Detail
                                    </a>
                                    <button onclick="showInterestModal({{ $auction->id }})" 
                                            class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition duration-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada lelang</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada lelang yang sesuai dengan kriteria pencarian Anda.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($auctions->hasPages())
                    <div class="mt-8">
                        {{ $auctions->links() }}
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <!-- Featured Auctions -->
                @if($featuredAuctions->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lelang Unggulan</h3>
                        <div class="space-y-4">
                            @foreach($featuredAuctions as $featured)
                                <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                    <h4 class="font-medium text-gray-900 mb-1">
                                        <a href="{{ route('auctions.show', $featured) }}" class="hover:text-blue-600 transition duration-300">
                                            {{ Str::limit($featured->title, 50) }}
                                        </a>
                                    </h4>
                                    <div class="text-sm text-gray-600 mb-1">{{ $featured->city }}</div>
                                    <div class="text-sm font-semibold text-green-600">{{ $featured->formatted_limit_price }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if($featured->auction_date)
                                            {{ $featured->auction_date->format('d M Y') }}
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upcoming Auctions -->
                @if($upcomingAuctions->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lelang Mendatang</h3>
                        <div class="space-y-4">
                            @foreach($upcomingAuctions as $upcoming)
                                <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                    <h4 class="font-medium text-gray-900 mb-1">
                                        <a href="{{ route('auctions.show', $upcoming) }}" class="hover:text-blue-600 transition duration-300">
                                            {{ Str::limit($upcoming->title, 50) }}
                                        </a>
                                    </h4>
                                    <div class="text-sm text-gray-600 mb-1">{{ $upcoming->city }}</div>
                                    <div class="text-sm font-semibold text-green-600">{{ $upcoming->formatted_limit_price }}</div>
                                    <div class="text-xs text-orange-600">{{ $upcoming->time_until_auction }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Interest Modal -->
    <div id="interest-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Nyatakan Minat</h3>
                <form id="interest-form" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="tel" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pesan (Opsional)</label>
                        <textarea name="message" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                            Kirim
                        </button>
                        <button type="button" onclick="hideInterestModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-md transition duration-300">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showInterestModal(auctionId) {
            document.getElementById('interest-form').action = `/auctions/${auctionId}/interest`;
            document.getElementById('interest-modal').classList.remove('hidden');
        }

        function hideInterestModal() {
            document.getElementById('interest-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('interest-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideInterestModal();
            }
        });
    </script>
    @endpush
</x-frontend-layout>