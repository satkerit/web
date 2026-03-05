@extends('frontend.layouts.app')

@section('title', $auction->title . ' - Lelang Agunan')

@push('head')
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $auction->meta_description ?? Str::limit($auction->description, 160) }}">
    <meta name="keywords" content="{{ $auction->meta_keywords ?? 'lelang agunan, ' . $auction->title . ', BPRS Babel' }}">
    <meta name="author" content="BPRS Bangka Belitung">
    <meta property="og:title" content="{{ $auction->title }} - Lelang Agunan">
    <meta property="og:description" content="{{ $auction->meta_description ?? Str::limit($auction->description, 160) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($auction->main_image)
        <meta property="og:image" content="{{ $auction->main_image }}">
    @endif
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-32 pb-24 overflow-hidden print:hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.03&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>

        <div class="container relative mx-auto px-4 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white mb-8 animate-fade-in-down">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-sm font-medium">Detail Lelang</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-6 animate-fade-in-down animation-delay-100 leading-tight">
                {{ $auction->title }}
            </h1>

            <!-- Breadcrumb -->
            <nav class="flex justify-center animate-fade-in-down animation-delay-300" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 bg-white/10 backdrop-blur-sm px-6 py-3 rounded-full border border-white/10">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-emerald-100 hover:text-white transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('auctions.index') }}" class="ml-1 text-sm font-medium text-emerald-100 hover:text-white transition-colors md:ml-2">Lelang</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-white md:ml-2 truncate max-w-[150px] md:max-w-xs">{{ Str::limit($auction->title, 30) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Print Brochure Layout (Clean & Professional) -->
    <div class="hidden print:block w-full max-w-[210mm] mx-auto p-8 bg-white text-black font-sans leading-relaxed">
        <!-- 1. Header: Logo & Company -->
        <div class="flex items-center justify-between border-b-2 border-emerald-600 pb-4 mb-6">
            <div class="flex items-center gap-4">
                <img src="{{ \App\Helpers\StorageHelper::url(\App\Models\CompanyInfo::getInfo()?->logo) }}" alt="Logo" class="h-16 w-auto object-contain">
                <div>
                    <h1 class="text-2xl font-bold uppercase tracking-wide text-emerald-800">BPRS Bangka Belitung</h1>
                    <p class="text-sm text-gray-600 font-medium">Mitra Amanah Usaha Anda</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Kode Lelang</p>
                <p class="text-xl font-mono font-bold text-gray-900">#{{ $auction->auction_number ?? $auction->id }}</p>
            </div>
        </div>

        <!-- 2. Hero Section: Title, Address, Price -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2 leading-tight">{{ $auction->title }}</h2>
            <p class="text-lg text-gray-600 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                {{ $auction->full_address ?: $auction->city }}
            </p>

            <div class="flex justify-between items-end bg-emerald-50 rounded-xl p-6 border border-emerald-100">
                <div>
                    <p class="text-sm text-emerald-700 font-semibold uppercase tracking-wider mb-1">Harga Limit</p>
                    <p class="text-4xl font-extrabold text-emerald-700">{{ $auction->formatted_limit_price }}</p>
                </div>
                @if($auction->estimated_price)
                    <div class="text-right">
                        <p class="text-xs text-gray-500 mb-1">Nilai Pasar / NJOP</p>
                        <p class="text-lg font-semibold text-gray-700">{{ $auction->formatted_estimated_price }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. Main Content Grid -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Left: Main Image -->
            <div class="col-span-1">
                <div class="aspect-[4/3] rounded-xl overflow-hidden border border-gray-200 shadow-sm mb-4">
                    @if($auction->images && count($auction->images) > 0)
                        <img src="{{ \App\Helpers\StorageHelper::url($auction->images[0]) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                    @endif
                </div>
                <!-- Secondary Images (Small) -->
                @if($auction->images && count($auction->images) > 1)
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(array_slice($auction->images, 1, 3) as $img)
                            <div class="aspect-square rounded-lg overflow-hidden border border-gray-100">
                                <img src="{{ \App\Helpers\StorageHelper::url($img) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Specifications & Details -->
            <div class="col-span-1 space-y-6">
                <!-- Specs Table -->
                <div>
                    <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-3">Spesifikasi Utama</h3>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-2 text-gray-500">Jenis Aset</td>
                                <td class="py-2 font-semibold text-right">{{ $auction->asset_type_label }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-500">Luas Tanah</td>
                                <td class="py-2 font-semibold text-right">{{ $auction->land_area ? number_format($auction->land_area, 0, ',', '.') . ' m²' : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-500">Luas Bangunan</td>
                                <td class="py-2 font-semibold text-right">{{ $auction->building_area ? number_format($auction->building_area, 0, ',', '.') . ' m²' : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-500">Legalitas</td>
                                <td class="py-2 font-semibold text-right">{{ $auction->certificate_type_label }}</td>
                            </tr>
                            @if($auction->bedrooms)
                            <tr>
                                <td class="py-2 text-gray-500">Kamar Tidur</td>
                                <td class="py-2 font-semibold text-right">{{ $auction->bedrooms }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Auction Schedule -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-3">Jadwal Lelang</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal</span>
                            <span class="font-semibold">{{ $auction->auction_date ? $auction->auction_date->translatedFormat('d F Y') : 'Belum ditentukan' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Waktu</span>
                            <span class="font-semibold">{{ $auction->auction_date ? $auction->auction_date->format('H:i') . ' WIB' : '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Metode</span>
                            <span class="font-semibold">{{ $auction->auction_type_label }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Description (Short) -->
        @if($auction->description)
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-3">Deskripsi Singkat</h3>
            <p class="text-sm text-gray-600 text-justify leading-relaxed line-clamp-4">
                {{ Str::limit(strip_tags($auction->description), 400) }}
            </p>
        </div>
        @endif

        <!-- 5. Footer: Contact & QR -->
        <div class="mt-auto border-t-2 border-emerald-600 pt-6 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <!-- QR Code -->
                <div class="bg-white p-2 border border-gray-200 rounded-lg shadow-sm">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ route('auctions.show', $auction->slug) }}" alt="QR" class="w-20 h-20">
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Info Lebih Lanjut & Pendaftaran</p>
                    <p class="font-bold text-emerald-800 text-lg">Hubungi BPRS Bangka Belitung</p>
                    <div class="flex gap-4 mt-2 text-sm text-gray-700">
                        <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> (0717) 432100</span>
                        <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg> www.bprsbabel.co.id</span>
                    </div>
                </div>
            </div>
            <div class="text-right text-xs text-gray-400">
                <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
                <p>Dokumen ini adalah alat bantu pemasaran.</p>
            </div>
        </div>
    </div>

    <!-- Content Section (Web View Only) -->
    <section class="py-12 md:py-20 bg-gray-50 print:hidden">
        <div class="container mx-auto px-4">

            <!-- Header Info Card -->
            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 p-6 md:p-8 mb-8 border border-gray-100 relative z-10 -mt-16 print:mt-0 print:shadow-none print:border-none print:p-0 print:mb-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-4 print:hidden">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                {{ $auction->asset_type_label }}
                            </span>
                            @if($auction->is_featured)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-lg">
                                    ⭐ Featured
                                </span>
                            @endif
                            @if($auction->is_urgent)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-gradient-to-r from-red-500 to-pink-600 text-white shadow-lg animate-pulse">
                                    🔥 Urgent
                                </span>
                            @endif
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold {{ $auction->status === 'active' ? 'bg-emerald-500 text-white' : ($auction->status === 'sold' ? 'bg-red-500 text-white' : 'bg-gray-500 text-white') }} shadow-lg">
                                <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                                {{ $auction->status_label }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-6 text-gray-600 print:hidden">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-medium">{{ $auction->full_address ?: $auction->city }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>{{ $auction->view_count ?? 0 }} kali dilihat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 print:block">
                <!-- Left Column: Content -->
                <div class="lg:col-span-2 space-y-8 print:w-full">

                    <!-- Sold Banner -->
                    @if($auction->status === 'sold')
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-6 md:p-8 text-white shadow-xl print:hidden">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 md:w-16 md:h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl md:text-2xl font-bold">Objek Telah Terjual</h2>
                                    @if($auction->sold_at)
                                        <p class="text-white/80 text-sm md:text-base">Terjual pada {{ $auction->sold_at->translatedFormat('d F Y') }}</p>
                                    @endif
                                </div>
                            </div>
                            @if($auction->winning_bid)
                                <div class="bg-white/10 rounded-xl p-4 md:p-6 backdrop-blur-sm">
                                    <p class="text-white/80 text-xs md:text-sm mb-1">Harga Terjual</p>
                                    <p class="text-3xl md:text-4xl font-bold">Rp {{ number_format($auction->winning_bid, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Image Gallery -->
                    @if($auction->images && count($auction->images) > 0)
                        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden print:shadow-none print:border-none print:mb-8"
                             x-data="{
                                activeImage: 0,
                                images: {{ json_encode(array_map(fn($img) => \App\Helpers\StorageHelper::url($img), $auction->images)) }},
                                next() { this.activeImage = (this.activeImage + 1) % this.images.length; },
                                prev() { this.activeImage = (this.activeImage - 1 + this.images.length) % this.images.length; }
                             }">
                            <div class="relative aspect-[16/10] md:aspect-[16/9] bg-gray-100 group">
                                <template x-for="(image, index) in images" :key="index">
                                    <img x-show="activeImage === index"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        :src="image" :alt="'Foto ' + (index + 1)"
                                        class="absolute inset-0 w-full h-full object-contain md:object-cover"
                                        :loading="index === 0 ? 'eager' : 'lazy'">
                                </template>

                                @if($auction->status === 'sold')
                                    <div class="absolute inset-0 bg-emerald-900/50 flex items-center justify-center z-10">
                                        <div class="text-center transform -rotate-12">
                                            <div class="inline-block border-4 border-white px-8 md:px-12 py-4 md:py-6 rounded-lg">
                                                <p class="text-white font-black text-3xl md:text-5xl tracking-wider">TERJUAL</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(count($auction->images) > 1)
                                    <button @click="prev()" class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 focus:outline-none z-20">
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button @click="next()" class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 focus:outline-none z-20">
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                @endif

                                <div class="absolute bottom-3 md:bottom-4 right-3 md:right-4 bg-black/60 text-white px-3 md:px-4 py-1.5 md:py-2 rounded-full text-xs md:text-sm font-medium backdrop-blur-sm z-20">
                                    <span x-text="activeImage + 1"></span> / {{ count($auction->images) }}
                                </div>
                            </div>

                            @if(count($auction->images) > 1)
                                    <div class="p-3 md:p-4 bg-gray-50 flex gap-2 md:gap-3 justify-start overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 print:hidden">
                                        @foreach($auction->images as $index => $image)
                                            <button @click="activeImage = {{ $index }}"
                                                :class="activeImage === {{ $index }} ? 'ring-2 ring-emerald-500 ring-offset-2' : 'opacity-60 hover:opacity-100'"
                                                class="flex-shrink-0 w-16 h-16 md:w-20 md:h-20 rounded-lg overflow-hidden transition-all focus:outline-none">
                                                <img src="{{ \App\Helpers\StorageHelper::url($image) }}" class="w-full h-full object-cover" loading="lazy">
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Print: Show only first 2 images side by side if available -->
                                    <div class="hidden print:grid print:grid-cols-2 print:gap-4 print:mt-4">
                                        @foreach(array_slice($auction->images, 1, 2) as $image)
                                            <div class="aspect-[4/3] overflow-hidden rounded-lg border border-gray-200">
                                                <img src="{{ \App\Helpers\StorageHelper::url($image) }}" class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- No Image Placeholder -->
                            <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100 print:shadow-none print:border-gray-200">
                            <div class="aspect-[16/10] md:aspect-[16/9] bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-20 h-20 md:w-24 md:h-24 text-emerald-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-emerald-700 font-medium">Tidak ada foto tersedia</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Specifications -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 print:shadow-none print:p-0 print:border print:border-gray-200 print:rounded-lg print:mb-6 print:break-inside-avoid">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3 print:mb-4 print:text-lg">
                            <span class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0 print:hidden">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            Spesifikasi Objek
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 print:grid-cols-3 print:gap-2">
                            <div class="p-4 md:p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl print:bg-none print:border print:border-gray-200 print:p-2">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2 print:mb-0">Jenis Aset</p>
                                <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->asset_type_label }}</p>
                            </div>
                            @if($auction->certificate_type)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl print:bg-none print:border print:border-gray-200 print:p-2">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2 print:mb-0">Sertifikat</p>
                                    <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->certificate_type_label }}</p>
                                </div>
                            @endif
                            @if($auction->land_area)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl print:bg-none print:border print:border-gray-200 print:p-2">
                                    <p class="text-xs text-emerald-600 uppercase tracking-wide font-semibold mb-2 print:text-gray-600 print:mb-0">Luas Tanah</p>
                                    <p class="font-bold text-emerald-700 text-lg md:text-xl print:text-black print:text-base">{{ number_format($auction->land_area, 0, ',', '.') }} m²</p>
                                </div>
                            @endif
                            @if($auction->building_area)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl print:bg-none print:border print:border-gray-200 print:p-2">
                                    <p class="text-xs text-emerald-600 uppercase tracking-wide font-semibold mb-2 print:text-gray-600 print:mb-0">Luas Bangunan</p>
                                    <p class="font-bold text-emerald-700 text-lg md:text-xl print:text-black print:text-base">{{ number_format($auction->building_area, 0, ',', '.') }} m²</p>
                                </div>
                            @endif
                            @if($auction->building_condition)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl print:bg-none print:border print:border-gray-200 print:p-2">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2 print:mb-0">Kondisi Bangunan</p>
                                    <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->building_condition }}</p>
                                </div>
                            @endif
                            @if($auction->bedrooms)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl print:bg-none print:border print:border-gray-200 print:p-2">
                                    <p class="text-xs text-emerald-600 uppercase tracking-wide font-semibold mb-2 print:text-gray-600 print:mb-0">Kamar Tidur</p>
                                    <p class="font-bold text-emerald-700 text-lg md:text-xl print:text-black print:text-base">{{ $auction->bedrooms }}</p>
                                </div>
                            @endif
                            @if($auction->bathrooms)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl print:bg-none print:border print:border-gray-200 print:p-2">
                                    <p class="text-xs text-emerald-600 uppercase tracking-wide font-semibold mb-2 print:text-gray-600 print:mb-0">Kamar Mandi</p>
                                    <p class="font-bold text-emerald-700 text-lg md:text-xl print:text-black print:text-base">{{ $auction->bathrooms }}</p>
                                </div>
                            @endif
                            @if($auction->floors)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl print:bg-none print:border print:border-gray-200 print:p-2">
                                    <p class="text-xs text-blue-600 uppercase tracking-wide font-semibold mb-2 print:text-gray-600 print:mb-0">Lantai</p>
                                    <p class="font-bold text-blue-700 text-lg md:text-xl print:text-black print:text-base">{{ $auction->floors }}</p>
                                </div>
                            @endif
                            @if($auction->parking_spaces)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl print:bg-none print:border print:border-gray-200 print:p-2">
                                    <p class="text-xs text-blue-600 uppercase tracking-wide font-semibold mb-2 print:text-gray-600 print:mb-0">Parkir</p>
                                    <p class="font-bold text-blue-700 text-lg md:text-xl print:text-black print:text-base">{{ $auction->parking_spaces }} mobil</p>
                                </div>
                            @endif
                            @if($auction->year_built)
                                <div class="p-4 md:p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl print:bg-none print:border print:border-gray-200 print:p-2">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2 print:mb-0">Tahun Dibangun</p>
                                    <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->year_built }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 print:shadow-none print:p-0 print:border print:border-gray-200 print:rounded-lg print:mb-6 print:break-inside-avoid">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3 print:text-lg">
                            <span class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0 print:hidden">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            Lokasi Objek
                        </h2>
                        <div class="bg-gray-50 rounded-xl p-4 md:p-6 print:bg-white print:border print:border-gray-200 print:p-2">
                            <p class="text-gray-700 text-base md:text-lg leading-relaxed print:text-black">{{ $auction->full_address ?: $auction->city }}</p>
                            @if($auction->nearby_facilities)
                                <div class="mt-4 pt-4 border-t border-gray-200 print:mt-2 print:pt-2">
                                    <h4 class="font-semibold text-gray-900 mb-2 print:mb-1">Fasilitas Terdekat:</h4>
                                    <p class="text-gray-700 text-sm md:text-base print:text-black">{!! nl2br(e($auction->nearby_facilities)) !!}</p>
                                </div>
                            @endif
                            @if($auction->transportation_access)
                                <div class="mt-4 pt-4 border-t border-gray-200 print:mt-2 print:pt-2">
                                    <h4 class="font-semibold text-gray-900 mb-2 print:mb-1">Akses Transportasi:</h4>
                                    <p class="text-gray-700 text-sm md:text-base print:text-black">{!! nl2br(e($auction->transportation_access)) !!}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    @if($auction->description)
                        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 print:shadow-none print:p-0 print:border print:border-gray-200 print:rounded-lg print:mb-6 print:break-inside-avoid">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3 print:text-lg">
                                <span class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0 print:hidden">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                </span>
                                Deskripsi
                            </h2>
                            <div class="prose prose-emerald max-w-none text-gray-700 text-sm md:text-base bg-gray-50 rounded-xl p-4 md:p-6 print:bg-white print:border print:border-gray-200 print:p-2 print:text-black">
                                {!! nl2br(e($auction->description)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Legal Information -->
                    @if($auction->legal_basis || $auction->court_decision || $auction->debt_amount || $auction->creditor_name || $auction->encumbrance_details)
                        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6 md:p-8 print:shadow-none print:p-0 print:border print:border-gray-200 print:rounded-lg print:mb-6 print:break-inside-avoid">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3 tracking-tight print:text-lg print:mb-4">
                                <span class="w-10 h-10 md:w-12 md:h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0 print:hidden">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </span>
                                Informasi Hukum
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 print:grid-cols-2 print:gap-4">
                                @if($auction->creditor_name)
                                    <div class="bg-gray-50 rounded-xl p-4 md:p-5 print:bg-white print:border print:border-gray-200 print:p-2">
                                        <h4 class="font-semibold text-gray-900 mb-2 print:mb-1">Nama Kreditur</h4>
                                        <p class="text-gray-700 text-sm md:text-base print:text-black">{{ $auction->creditor_name }}</p>
                                    </div>
                                @endif
                                @if($auction->encumbrance_details)
                                    <div class="bg-gray-50 rounded-xl p-4 md:p-5 print:bg-white print:border print:border-gray-200 print:p-2">
                                        <h4 class="font-semibold text-gray-900 mb-2 print:mb-1">Hak Tanggungan</h4>
                                        <p class="text-gray-700 text-sm md:text-base print:text-black">{!! nl2br(e($auction->encumbrance_details)) !!}</p>
                                    </div>
                                @endif
                                @if($auction->legal_basis)
                                    <div class="bg-gray-50 rounded-xl p-4 md:p-5 print:bg-white print:border print:border-gray-200 print:p-2">
                                        <h4 class="font-semibold text-gray-900 mb-2 print:mb-1">Dasar Hukum</h4>
                                        <p class="text-gray-700 text-sm md:text-base print:text-black">{!! nl2br(e($auction->legal_basis)) !!}</p>
                                    </div>
                                @endif
                                @if($auction->court_decision)
                                    <div class="bg-gray-50 rounded-xl p-4 md:p-5 print:bg-white print:border print:border-gray-200 print:p-2">
                                        <h4 class="font-semibold text-gray-900 mb-2 print:mb-1">Putusan Pengadilan</h4>
                                        <p class="text-gray-700 text-sm md:text-base print:text-black">{{ $auction->court_decision }}</p>
                                        @if($auction->court_decision_date)
                                            <p class="text-gray-600 text-xs mt-1 print:text-black">Tanggal: {{ $auction->court_decision_date->format('d F Y') }}</p>
                                        @endif
                                    </div>
                                @endif
                                @if($auction->debt_amount)
                                    <div class="bg-red-50 rounded-xl p-4 md:p-5 md:col-span-2 print:bg-white print:border print:border-red-200 print:p-2 print:col-span-2">
                                        <h4 class="font-semibold text-red-900 mb-2 print:text-black">Jumlah Hutang</h4>
                                        <p class="text-red-700 text-lg md:text-xl font-bold print:text-black">Rp {{ number_format($auction->debt_amount, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Viewing Schedule -->
                    @if($auction->viewing_schedule || $auction->viewing_start || $auction->viewing_end)
                        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                <span class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </span>
                                Jadwal Open House
                            </h2>
                            <div class="bg-blue-50 rounded-xl p-4 md:p-6">
                                @if($auction->viewing_start && $auction->viewing_end)
                                    <div class="mb-4">
                                        <h4 class="font-semibold text-blue-900 mb-2">Periode Viewing</h4>
                                        <p class="text-blue-700">{{ $auction->viewing_start->format('d F Y') }} - {{ $auction->viewing_end->format('d F Y') }}</p>
                                    </div>
                                @endif
                                @if($auction->viewing_schedule)
                                    <div>
                                        <h4 class="font-semibold text-blue-900 mb-2">Jadwal Detail</h4>
                                        <p class="text-blue-700 text-sm md:text-base">{!! nl2br(e($auction->viewing_schedule)) !!}</p>
                                    </div>
                                @endif
                                @if($auction->viewing_contact)
                                    <div class="mt-4 pt-4 border-t border-blue-200">
                                        <h4 class="font-semibold text-blue-900 mb-2">Kontak Viewing</h4>
                                        <p class="text-blue-700 text-sm md:text-base">{{ $auction->viewing_contact }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Terms & Conditions -->
                    @if($auction->terms_conditions)
                        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                <span class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                Syarat & Ketentuan
                            </h2>
                            <div class="prose prose-emerald max-w-none text-gray-700 bg-yellow-50 rounded-xl p-4 md:p-6 border border-yellow-100">
                                {!! nl2br(e($auction->terms_conditions)) !!}
                            </div>
                            @if($auction->special_conditions)
                                <div class="mt-4">
                                    <h4 class="font-semibold text-gray-900 mb-2">Ketentuan Khusus</h4>
                                    <div class="prose prose-emerald max-w-none text-gray-700 bg-orange-50 rounded-xl p-4 md:p-6 border border-orange-100">
                                        {!! nl2br(e($auction->special_conditions)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Documents -->
                    @if($auction->documents && count($auction->documents) > 0)
                        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3 tracking-tight">
                                <span class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </span>
                                Dokumen Pendukung
                            </h2>
                            <div class="grid gap-3">
                                @foreach($auction->documents as $doc)
                                    <a href="{{ \App\Helpers\StorageHelper::url($doc['path'] ?? $doc) }}" target="_blank"
                                        class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-emerald-50 transition-colors group border border-gray-100">
                                        <svg class="w-8 h-8 md:w-10 md:h-10 text-red-500 mr-3 md:mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                        </svg>
                                        <span class="text-gray-700 group-hover:text-emerald-600 font-medium flex-1 text-sm md:text-base truncate">{{ $doc['name'] ?? basename($doc['path'] ?? $doc) }}</span>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Sidebar -->
                <div class="lg:col-span-1 print:hidden">
                    <div class="lg:sticky lg:top-24 space-y-6">
                        <!-- Price & Info Card -->
                        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 border border-gray-100">
                            <!-- Price -->
                            <div class="text-center mb-6 pb-6 border-b border-gray-100">
                                <p class="text-xs md:text-sm text-gray-500 mb-2 uppercase tracking-wide font-semibold">Harga Limit</p>
                                <h2 class="text-3xl md:text-4xl font-bold text-emerald-600">
                                    {{ $auction->formatted_limit_price }}
                                </h2>
                                @if($auction->estimated_price)
                                    <p class="text-xs md:text-sm text-gray-500 mt-3">NJOP/Estimasi: <span class="font-semibold text-gray-700">{{ $auction->formatted_estimated_price }}</span></p>
                                @endif
                            </div>

                            <!-- Auction Info -->
                            <div class="space-y-4 md:space-y-5 mb-6 md:mb-8">
                                <div class="flex items-start gap-3 md:gap-4">
                                    <div class="w-9 h-9 md:w-10 md:h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Tanggal Lelang</p>
                                        @if($auction->auction_date)
                                            <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->auction_date->translatedFormat('d F Y') }}</p>
                                            <p class="text-xs md:text-sm text-gray-600">{{ $auction->auction_date->format('H:i') }} WIB</p>
                                        @else
                                            <p class="font-bold text-gray-900 text-sm md:text-base">Belum ditentukan</p>
                                        @endif
                                    </div>
                                </div>

                                @if($auction->registration_start || $auction->registration_end)
                                    <div class="flex items-start gap-3 md:gap-4">
                                        <div class="w-9 h-9 md:w-10 md:h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Pendaftaran</p>
                                            @if($auction->registration_start && $auction->registration_end)
                                                <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->registration_start->format('d M') }} - {{ $auction->registration_end->format('d M Y') }}</p>
                                            @elseif($auction->registration_end)
                                                <p class="font-bold text-gray-900 text-sm md:text-base">Sampai {{ $auction->registration_end->format('d M Y') }}</p>
                                            @else
                                                <p class="font-bold text-gray-900 text-sm md:text-base">Belum ditentukan</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($auction->auction_location)
                                    <div class="flex items-start gap-3 md:gap-4">
                                        <div class="w-9 h-9 md:w-10 md:h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Tempat Lelang</p>
                                            <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->auction_location }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($auction->auction_type)
                                    <div class="flex items-start gap-3 md:gap-4">
                                        <div class="w-9 h-9 md:w-10 md:h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 md:w-5 md:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Jenis Lelang</p>
                                            <p class="font-bold text-gray-900 text-sm md:text-base">{{ $auction->auction_type_label }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Countdown Timer -->
                            @if($auction->auction_date && $auction->days_until_auction >= 0)
                                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-4 md:p-5 mb-6 border border-emerald-100 print:hidden">
                                    <p class="text-xs text-emerald-700 font-bold uppercase tracking-wide mb-2 text-center">Waktu Tersisa</p>
                                    <div class="flex justify-center gap-3 md:gap-4 text-center" x-data="countdown('{{ $auction->auction_date->format('Y-m-d H:i:s') }}')">
                                        <div>
                                            <div class="bg-white rounded-lg p-2 shadow-sm border border-emerald-100 min-w-[3.5rem] md:min-w-[4rem]">
                                                <span class="block text-xl md:text-2xl font-bold text-emerald-600" x-text="days">0</span>
                                                <span class="text-[10px] md:text-xs text-gray-500 font-medium">Hari</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="bg-white rounded-lg p-2 shadow-sm border border-emerald-100 min-w-[3.5rem] md:min-w-[4rem]">
                                                <span class="block text-xl md:text-2xl font-bold text-emerald-600" x-text="hours">0</span>
                                                <span class="text-[10px] md:text-xs text-gray-500 font-medium">Jam</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="bg-white rounded-lg p-2 shadow-sm border border-emerald-100 min-w-[3.5rem] md:min-w-[4rem]">
                                                <span class="block text-xl md:text-2xl font-bold text-emerald-600" x-text="minutes">0</span>
                                                <span class="text-[10px] md:text-xs text-gray-500 font-medium">Menit</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="space-y-3 print:hidden">
                                <button onclick="shareAuction()"
                                        class="w-full py-4 bg-white text-gray-700 font-bold text-center rounded-xl border border-gray-200 hover:bg-gray-50 hover:border-emerald-200 hover:text-emerald-600 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                    </svg>
                                    Bagikan
                                </button>

                                <button onclick="window.print()" class="w-full py-4 bg-gray-50 text-gray-600 font-bold text-center rounded-xl border border-gray-200 hover:bg-gray-100 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Cetak Brosur
                                </button>
                            </div>
                        </div>

                        <!-- Safety Info -->
                        <div class="bg-blue-50 rounded-2xl border border-blue-100 p-6 print:hidden">
                            <h4 class="font-bold text-blue-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Penting
                            </h4>
                            <p class="text-sm text-blue-700 leading-relaxed">
                                Pastikan Anda telah membaca syarat dan ketentuan lelang sebelum melakukan penawaran. Hati-hati terhadap penipuan yang mengatasnamakan BPR.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        function shareAuction() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $auction->title }}',
                    text: 'Cek lelang ini: {{ $auction->title }}',
                    url: '{{ url()->current() }}',
                })
                .catch((error) => console.log('Error sharing', error));
            } else {
                var dummy = document.createElement('input'),
                text = window.location.href;
                document.body.appendChild(dummy);
                dummy.value = text;
                dummy.select();
                document.execCommand('copy');
                document.body.removeChild(dummy);
                alert('Link lelang berhasil disalin!');
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', (endTime) => ({
                days: '00',
                hours: '00',
                minutes: '00',
                seconds: '00',
                endTime: new Date(endTime).getTime(),
                timer: null,
                init() {
                    this.updateCountdown();
                    this.timer = setInterval(() => {
                        this.updateCountdown();
                    }, 1000);
                },
                updateCountdown() {
                    const now = new Date().getTime();
                    const distance = this.endTime - now;
                    if (distance < 0) {
                        clearInterval(this.timer);
                        return;
                    }
                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                }
            }))
        })
    </script>
    @endpush
@endsection
