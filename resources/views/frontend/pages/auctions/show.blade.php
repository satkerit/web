<x-frontend-layout>
    <x-slot name="title">{{ $auction->title }} - Lelang</x-slot>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-24 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm mb-6">
                <a href="{{ route('home') }}" class="text-white/70 hover:text-white transition-colors">Beranda</a>
                <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('auctions.index') }}" class="text-white/70 hover:text-white transition-colors">Lelang</a>
                <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white font-medium">Detail</span>
            </nav>

            <!-- Status Badges -->
            <div class="flex flex-wrap items-center gap-3 mb-6">
                @php $colors = $auction->status_color; @endphp
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold {{ $colors['bg'] }} {{ $colors['text'] }} shadow-lg">
                    @if($auction->status === 'sold')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @else
                        <span class="w-2 h-2 rounded-full {{ $colors['dot'] }} animate-pulse"></span>
                    @endif
                    {{ $auction->status_label }}
                </span>
                @if($auction->auction_type)
                    <span class="px-4 py-2 text-sm font-medium rounded-full bg-white/20 text-white backdrop-blur-sm">
                        {{ \App\Models\Auction::$auctionTypes[$auction->auction_type] ?? $auction->auction_type }}
                    </span>
                @endif
                <span class="px-4 py-2 text-sm font-medium rounded-full bg-white/20 text-white backdrop-blur-sm">
                    {{ \App\Models\Auction::$assetTypes[$auction->asset_type] ?? $auction->asset_type }}
                </span>
            </div>

            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">{{ $auction->title }}</h1>
            @if($auction->object_number)
                <p class="text-white/80 text-lg">No. Objek: <span class="font-semibold">{{ $auction->object_number }}</span></p>
            @endif
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 -mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Sold Banner -->
                    @if($auction->status === 'sold')
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-3xl p-8 text-white shadow-2xl">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold">Objek Telah Terjual</h2>
                                @if($auction->sold_at)
                                    <p class="text-white/80">Terjual pada {{ $auction->sold_at->translatedFormat('d F Y') }}</p>
                                @endif
                            </div>
                        </div>
                        @if($auction->winning_bid)
                        <div class="bg-white/10 rounded-2xl p-6 backdrop-blur-sm">
                            <p class="text-white/80 text-sm mb-1">Harga Terjual</p>
                            <p class="text-4xl font-bold">Rp {{ number_format($auction->winning_bid, 0, ',', '.') }}</p>
                            @if($auction->winner_name)
                                <p class="text-white/80 mt-2">Pemenang: <span class="font-semibold text-white">{{ $auction->winner_name }}</span></p>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Image Gallery -->
                    @if($auction->images && count($auction->images) > 0)
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden" x-data="{
                        activeImage: 0,
                        images: {{ json_encode($auction->images) }},
                        next() { this.activeImage = (this.activeImage + 1) % this.images.length; },
                        prev() { this.activeImage = (this.activeImage - 1 + this.images.length) % this.images.length; }
                    }">
                        <div class="relative aspect-[16/10]">
                            <template x-for="(image, index) in images" :key="index">
                                <img x-show="activeImage === index" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" :src="'/storage/' + image" :alt="'Foto ' + (index + 1)" class="absolute inset-0 w-full h-full object-cover">
                            </template>
                            @if($auction->status === 'sold')
                            <div class="absolute inset-0 bg-emerald-900/50 flex items-center justify-center">
                                <div class="text-center transform -rotate-12">
                                    <div class="inline-block border-4 border-white px-12 py-6 rounded-lg">
                                        <p class="text-white font-black text-5xl tracking-wider">TERJUAL</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(count($auction->images) > 1)
                            <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110">
                                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110">
                                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                            @endif
                            <div class="absolute bottom-4 right-4 bg-black/60 text-white px-4 py-2 rounded-full text-sm font-medium backdrop-blur-sm">
                                <span x-text="activeImage + 1"></span> / {{ count($auction->images) }}
                            </div>
                        </div>
                        @if(count($auction->images) > 1)
                        <div class="p-4 bg-gray-50 flex gap-3 justify-center overflow-x-auto">
                            @foreach($auction->images as $index => $image)
                            <button @click="activeImage = {{ $index }}" :class="activeImage === {{ $index }} ? 'ring-2 ring-emerald-500 ring-offset-2' : 'opacity-60 hover:opacity-100'" class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden transition-all">
                                <img src="{{ Storage::url($image) }}" class="w-full h-full object-cover">
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                        <div class="aspect-[16/10] bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-24 h-24 text-emerald-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-emerald-600 font-medium text-lg">Belum ada foto</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Specifications -->
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </span>
                            Spesifikasi Objek
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">Jenis Aset</p>
                                <p class="font-bold text-gray-900">{{ \App\Models\Auction::$assetTypes[$auction->asset_type] ?? $auction->asset_type }}</p>
                            </div>
                            @if($auction->certificate_type)
                            <div class="p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">Sertifikat</p>
                                <p class="font-bold text-gray-900">{{ \App\Models\Auction::$certificateTypes[$auction->certificate_type] ?? $auction->certificate_type }}</p>
                            </div>
                            @endif
                            @if($auction->land_area)
                            <div class="p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl">
                                <p class="text-xs text-emerald-600 uppercase tracking-wide font-semibold mb-2">Luas Tanah</p>
                                <p class="font-bold text-emerald-700 text-xl">{{ number_format($auction->land_area, 0, ',', '.') }} m²</p>
                            </div>
                            @endif
                            @if($auction->building_area)
                            <div class="p-5 bg-gradient-to-br from-teal-50 to-teal-100 rounded-2xl">
                                <p class="text-xs text-teal-600 uppercase tracking-wide font-semibold mb-2">Luas Bangunan</p>
                                <p class="font-bold text-teal-700 text-xl">{{ number_format($auction->building_area, 0, ',', '.') }} m²</p>
                            </div>
                            @endif
                            @if($auction->debtor_name)
                            <div class="p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">Debitur</p>
                                <p class="font-bold text-gray-900">{{ $auction->debtor_name }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                            <span class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </span>
                            Lokasi Objek
                        </h2>
                        <p class="text-gray-700 text-lg leading-relaxed">{{ $auction->location }}</p>
                    </div>

                    <!-- Description -->
                    @if($auction->description)
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                            <span class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </span>
                            Deskripsi
                        </h2>
                        <div class="prose prose-gray max-w-none text-gray-700">{!! nl2br(e($auction->description)) !!}</div>
                    </div>
                    @endif

                    <!-- Viewing Schedule -->
                    @if($auction->viewing_schedule)
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                            <span class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </span>
                            Jadwal Open House
                        </h2>
                        <p class="text-gray-700">{!! nl2br(e($auction->viewing_schedule)) !!}</p>
                    </div>
                    @endif

                    <!-- Terms -->
                    @if($auction->terms_conditions)
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                            <span class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            Syarat & Ketentuan
                        </h2>
                        <div class="prose prose-gray prose-sm max-w-none text-gray-700">{!! nl2br(e($auction->terms_conditions)) !!}</div>
                    </div>
                    @endif

                    <!-- Documents -->
                    @if($auction->documents && count($auction->documents) > 0)
                    <div class="bg-white rounded-3xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                            Dokumen Pendukung
                        </h2>
                        <div class="grid gap-3">
                            @foreach($auction->documents as $doc)
                            <a href="{{ Storage::url($doc['path'] ?? $doc) }}" target="_blank" class="flex items-center p-4 bg-gray-50 rounded-2xl hover:bg-emerald-50 transition-colors group">
                                <svg class="w-10 h-10 text-red-500 mr-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/></svg>
                                <span class="text-gray-700 group-hover:text-emerald-600 font-medium flex-1">{{ $doc['name'] ?? basename($doc['path'] ?? $doc) }}</span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Price Card -->
                    <div class="bg-white rounded-3xl shadow-xl p-8 sticky top-24">
                        <!-- Price -->
                        <div class="text-center mb-8 pb-8 border-b border-gray-100">
                            <p class="text-sm text-gray-500 mb-2">Harga Limit</p>
                            <p class="text-4xl font-bold text-emerald-600">Rp {{ number_format($auction->starting_price, 0, ',', '.') }}</p>
                            @if($auction->estimated_price)
                            <p class="text-sm text-gray-500 mt-3">NJOP/Estimasi: <span class="font-semibold text-gray-700">Rp {{ number_format($auction->estimated_price, 0, ',', '.') }}</span></p>
                            @endif
                        </div>

                        <!-- Auction Info -->
                        <div class="space-y-5 mb-8">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Tanggal Lelang</p>
                                    <p class="font-bold text-gray-900">{{ $auction->auction_date->translatedFormat('d F Y') }}</p>
                                    <p class="text-sm text-gray-600">{{ $auction->auction_date->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            @if($auction->registration_deadline)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Batas Pendaftaran</p>
                                    <p class="font-bold text-gray-900">{{ $auction->registration_deadline->translatedFormat('d F Y') }}</p>
                                    <p class="text-sm text-gray-600">{{ $auction->registration_deadline->format('H:i') }} WIB</p>
                                </div>
                            </div>
                            @endif

                            @if($auction->auction_location)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Tempat Lelang</p>
                                    <p class="font-bold text-gray-900">{{ $auction->auction_location }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Deposit -->
                        @php $deposit = $auction->calculated_deposit; @endphp
                        @if($deposit)
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-5 mb-6 border border-emerald-200">
                            <p class="text-xs text-emerald-700 font-bold uppercase tracking-wide mb-2">Uang Jaminan</p>
                            <p class="text-2xl font-bold text-emerald-700">Rp {{ number_format($deposit, 0, ',', '.') }}</p>
                            @if($auction->deposit_percentage)
                            <p class="text-xs text-emerald-600 mt-1">({{ $auction->deposit_percentage }}% dari harga limit)</p>
                            @endif
                        </div>
                        @endif

                        <!-- Bank Info -->
                        @if($auction->bank_name && $auction->bank_account)
                        <div class="bg-gray-50 rounded-2xl p-5 mb-6">
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wide mb-3">Transfer Jaminan</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $auction->bank_name }}</p>
                            <p class="text-2xl font-mono font-bold text-gray-900 my-2">{{ $auction->bank_account }}</p>
                            @if($auction->account_holder)
                            <p class="text-sm text-gray-600">a.n. {{ $auction->account_holder }}</p>
                            @endif
                        </div>
                        @endif

                        <!-- Contact -->
                        @if($auction->contact_person || $auction->contact_phone)
                        <div class="border-t border-gray-100 pt-6 mb-6">
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wide mb-3">Contact Person</p>
                            @if($auction->contact_person)
                            <p class="font-bold text-gray-900 text-lg">{{ $auction->contact_person }}</p>
                            @endif
                            @if($auction->contact_phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $auction->contact_phone) }}" class="inline-flex items-center gap-2 mt-2 text-emerald-600 hover:text-emerald-700 font-semibold">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                {{ $auction->contact_phone }}
                            </a>
                            @endif
                        </div>
                        @endif

                        <!-- Status -->
                        @if($auction->status === 'sold')
                        <div class="text-center py-5 bg-emerald-100 rounded-2xl text-emerald-700 font-bold">
                            <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Objek Telah Terjual
                        </div>
                        @elseif($auction->can_register)
                        <div class="text-center py-5 bg-emerald-100 rounded-2xl text-emerald-700 font-bold">
                            <span class="inline-block w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                            Pendaftaran Terbuka
                        </div>
                        @else
                        <div class="text-center py-5 bg-gray-100 rounded-2xl text-gray-500 font-bold">
                            Pendaftaran Ditutup
                        </div>
                        @endif

                        @if($auction->kpknl_office)
                        <p class="text-xs text-center text-gray-500 mt-4">Melalui {{ $auction->kpknl_office }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-12">
                <a href="{{ route('auctions.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold text-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Daftar Lelang
                </a>
            </div>
        </div>
    </section>
</x-frontend-layout>
