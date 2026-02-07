<x-frontend-layout>
    <x-slot:title>Berita & Artikel - {{ config('app.name') }}</x-slot:title>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 py-20 md:py-24 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>

        <!-- Animated Gradient Orbs -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl animate-blob"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <span class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-emerald-50 text-sm font-medium mb-6 ring-1 ring-white/20">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Informasi Terkini
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight tracking-tight">Berita & Artikel</h1>
            <p class="text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">Dapatkan wawasan terbaru seputar ekonomi syariah, kegiatan BPRS Bangka Belitung, dan tips keuangan bermanfaat.</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 md:py-16 bg-gray-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search & Filter Card -->
            <div class="mb-10 bg-white rounded-2xl shadow-xl shadow-gray-200/50 p-6 border border-gray-100 relative -mt-24 z-10">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <div class="relative group">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita berdasarkan judul..." class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-shadow">
                        </div>
                    </div>
                    <div class="w-full md:w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white cursor-pointer hover:bg-gray-50 transition-colors">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full md:w-auto px-8 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 transform hover:-translate-y-0.5">
                            Cari
                        </button>
                    </div>
                </form>

                <!-- Active Filters -->
                @if(request('search') || request('category'))
                <div class="flex flex-wrap items-center gap-2 pt-4 mt-4 border-t border-gray-100 animate-fade-in-up">
                    <span class="text-sm text-gray-500 font-medium">Filter aktif:</span>
                    @if(request('search'))
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full border border-emerald-200">
                            "{{ request('search') }}"
                            <a href="{{ route('news.index', request()->except('search')) }}" class="hover:text-emerald-900 ml-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        </span>
                    @endif
                    @if(request('category'))
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full border border-emerald-200">
                            {{ request('category') }}
                            <a href="{{ route('news.index', request()->except('category')) }}" class="hover:text-emerald-900 ml-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        </span>
                    @endif
                    <a href="{{ route('news.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 font-medium ml-2 transition-colors">
                        Reset Filter
                    </a>
                </div>
                @endif
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($news as $item)
                <article class="group bg-white rounded-2xl overflow-hidden shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-emerald-900/10 transition-all duration-300 border border-gray-100 hover:border-emerald-200 flex flex-col h-full">
                    <!-- Image -->
                    <div class="relative h-64 overflow-hidden bg-gray-100">
                        @if($item->featured_image)
                        <img src="{{ \App\Helpers\StorageHelper::url($item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-20 h-20 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif

                        <!-- Overlay on hover -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Category Badge -->
                        @if($item->category)
                        <span class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm text-emerald-700 text-xs font-bold rounded-full shadow-lg border border-white/50">
                            {{ $item->category }}
                        </span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex flex-col flex-grow">
                        <!-- Date -->
                        <div class="flex items-center gap-2 text-xs font-medium text-gray-500 mb-3 uppercase tracking-wider">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <time datetime="{{ $item->published_at->toISOString() }}">{{ $item->published_at->translatedFormat('d F Y') }}</time>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors leading-tight">
                            <a href="{{ route('news.show', $item->slug) }}">
                                {{ $item->title }}
                            </a>
                        </h3>

                        <!-- Excerpt -->
                        <p class="text-gray-600 text-sm mb-6 line-clamp-3 leading-relaxed flex-grow">{{ $item->excerpt }}</p>

                        <!-- Read More -->
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <a href="{{ route('news.show', $item->slug) }}" class="inline-flex items-center gap-2 text-emerald-600 font-bold text-sm group/link hover:text-emerald-700">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
                @empty
                <!-- Empty State -->
                <div class="col-span-full">
                    <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50">
                        <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Tidak Ada Berita</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-8">Belum ada berita yang sesuai dengan kriteria pencarian Anda. Silakan coba dengan kata kunci lain.</p>
                        <a href="{{ route('news.index') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20">
                            Reset Pencarian
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($news->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $news->appends(request()->query())->links('pagination.custom') }}
            </div>
            @endif
        </div>
    </section>
</x-frontend-layout>
