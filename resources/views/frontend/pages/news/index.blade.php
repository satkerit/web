<x-frontend-layout>
    <x-slot:title>Berita & Artikel - {{ config('app.name') }}</x-slot:title>

    <!-- Hero Section -->
    <section class="relative pt-24 sm:pt-28 md:pt-32 pb-12 sm:pb-16 md:pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.03&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-white/10 backdrop-blur-sm rounded-full text-teal-100 text-xs sm:text-sm font-medium mb-4 sm:mb-6 animate-slide-up">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Informasi Terkini
            </span>
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-3 sm:mb-4 md:mb-6 leading-tight tracking-tight animate-slide-up delay-100 px-4">Berita & Artikel</h1>
            <p class="text-base sm:text-lg md:text-xl text-white/80 max-w-2xl mx-auto leading-relaxed animate-slide-up delay-200 px-4">Dapatkan wawasan terbaru seputar ekonomi syariah, kegiatan BPRS Bangka Belitung, dan tips keuangan bermanfaat.</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 md:py-16 bg-gray-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search & Filter Card -->
            <div class="mb-8 sm:mb-10 bg-white rounded-xl sm:rounded-2xl shadow-xl shadow-gray-200/50 p-4 sm:p-6 border border-gray-100 relative -mt-20 sm:-mt-24 z-10">
                <form method="GET" class="flex flex-col gap-3 sm:gap-4">
                    <div class="flex-1">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Pencarian</label>
                        <div class="relative group">
                            <svg class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-shadow touch-manipulation">
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <div class="flex-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Kategori</label>
                            <select name="category" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-200 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white cursor-pointer hover:bg-gray-50 transition-colors touch-manipulation">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full sm:w-auto min-h-[44px] sm:min-h-[48px] px-6 sm:px-8 py-2.5 sm:py-3 bg-emerald-600 text-white text-sm sm:text-base font-bold rounded-lg sm:rounded-xl hover:bg-emerald-700 active:scale-98 transition-all shadow-lg shadow-emerald-600/20 transform hover:-translate-y-0.5 touch-manipulation">
                                Cari
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Active Filters -->
                @if(request('search') || request('category'))
                <div class="flex flex-wrap items-center gap-2 pt-3 sm:pt-4 mt-3 sm:mt-4 border-t border-gray-100 animate-fade-in-up">
                    <span class="text-xs sm:text-sm text-gray-500 font-medium">Filter aktif:</span>
                    @if(request('search'))
                        <span class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 bg-emerald-100 text-emerald-700 text-xs sm:text-sm font-medium rounded-full border border-emerald-200">
                            <span class="truncate max-w-[120px] sm:max-w-none">"{{ request('search') }}"</span>
                            <a href="{{ route('news.index', request()->except('search')) }}" class="hover:text-emerald-900 flex-shrink-0 min-w-[16px] min-h-[16px] flex items-center justify-center touch-manipulation active:scale-95">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        </span>
                    @endif
                    @if(request('category'))
                        <span class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 bg-emerald-100 text-emerald-700 text-xs sm:text-sm font-medium rounded-full border border-emerald-200">
                            {{ request('category') }}
                            <a href="{{ route('news.index', request()->except('category')) }}" class="hover:text-emerald-900 flex-shrink-0 min-w-[16px] min-h-[16px] flex items-center justify-center touch-manipulation active:scale-95">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        </span>
                    @endif
                    <a href="{{ route('news.index') }}" class="text-xs sm:text-sm text-gray-500 hover:text-emerald-600 font-medium ml-1 sm:ml-2 transition-colors min-h-[44px] sm:min-h-0 flex items-center touch-manipulation">
                        Reset Filter
                    </a>
                </div>
                @endif
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                @forelse($news as $item)
                <article class="group bg-white rounded-xl sm:rounded-2xl overflow-hidden shadow-lg sm:shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-emerald-900/10 transition-all duration-300 border border-gray-100 hover:border-emerald-200 flex flex-col h-full touch-manipulation active:scale-[0.99]">
                    <!-- Image -->
                    <div class="relative h-48 sm:h-56 md:h-64 overflow-hidden bg-gray-100">
                        @if($item->featured_image)
                        <img src="{{ \App\Helpers\StorageHelper::url($item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-16 h-16 sm:w-20 sm:h-20 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif

                        <!-- Overlay on hover -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Category Badge -->
                        @if($item->category)
                        <span class="absolute top-3 left-3 sm:top-4 sm:left-4 px-2.5 py-1 sm:px-3 text-emerald-700 text-[10px] sm:text-xs font-bold rounded-full shadow-lg border border-white/50 bg-white/90 backdrop-blur-sm">
                            {{ $item->category }}
                        </span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-4 sm:p-5 md:p-6 flex flex-col flex-grow">
                        <!-- Date -->
                        <div class="flex items-center gap-1.5 sm:gap-2 text-[10px] sm:text-xs font-medium text-gray-500 mb-2 sm:mb-3 uppercase tracking-wider">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <time datetime="{{ $item->published_at->toISOString() }}">{{ $item->published_at->translatedFormat('d F Y') }}</time>
                        </div>

                        <!-- Title -->
                        <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-2 sm:mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors leading-tight">
                            <a href="{{ route('news.show', $item->slug) }}" class="touch-manipulation">
                                {{ $item->title }}
                            </a>
                        </h3>

                        <!-- Excerpt -->
                        <p class="text-gray-600 text-xs sm:text-sm mb-4 sm:mb-6 line-clamp-3 leading-relaxed flex-grow">{{ $item->excerpt }}</p>

                        <!-- Read More -->
                        <div class="pt-3 sm:pt-4 border-t border-gray-100 flex items-center justify-between">
                            <a href="{{ route('news.show', $item->slug) }}" class="inline-flex items-center gap-1.5 sm:gap-2 text-emerald-600 font-bold text-xs sm:text-sm group/link hover:text-emerald-700 min-h-[44px] sm:min-h-0 -my-2 sm:my-0 touch-manipulation active:scale-95">
                                Baca Selengkapnya
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 group-hover/link:translate-x-1 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
                @empty
                <!-- Empty State -->
                <div class="col-span-full">
                    <div class="text-center py-12 sm:py-16 md:py-20 bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-lg sm:shadow-xl shadow-gray-200/50 px-4">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">Tidak Ada Berita</h3>
                        <p class="text-sm sm:text-base text-gray-500 max-w-md mx-auto mb-6 sm:mb-8 leading-relaxed">Belum ada berita yang sesuai dengan kriteria pencarian Anda. Silakan coba dengan kata kunci lain.</p>
                        <a href="{{ route('news.index') }}" class="inline-flex items-center min-h-[48px] px-5 sm:px-6 py-2.5 sm:py-3 bg-emerald-600 text-white text-sm sm:text-base font-bold rounded-lg sm:rounded-xl hover:bg-emerald-700 active:scale-98 transition shadow-lg shadow-emerald-600/20 touch-manipulation">
                            Reset Pencarian
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($news->hasPages())
            <div class="mt-8 sm:mt-10 md:mt-12 flex justify-center px-4">
                {{ $news->appends(request()->query())->links('pagination.custom') }}
            </div>
            @endif
        </div>
    </section>
</x-frontend-layout>
