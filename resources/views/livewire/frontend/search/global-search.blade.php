<div class="relative" x-data="{ open: @entangle('showResults').live }" @click.away="open = false">
    <!-- Search Input -->
    <div class="relative">
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            @focus="if(query.length >= 2) open = true"
            placeholder="Cari berita, produk, lelang..."
            class="w-full px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>

        @if($query)
        <button wire:click="$set('query', '')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        @endif
    </div>

    <!-- Search Results Dropdown -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute z-50 w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-200 max-h-96 overflow-y-auto"
        style="display: none;"
    >
        @if(count($results) > 0)
            <div class="p-2">
                @foreach($results as $result)
                <a href="{{ $result['url'] }}" class="block p-3 hover:bg-gray-50 rounded-lg transition-colors" wire:click="closeResults">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                {{ $result['type'] === 'Berita' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $result['type'] === 'Produk' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $result['type'] === 'Lelang' ? 'bg-purple-100 text-purple-700' : '' }}
                            ">
                                {{ $result['type'] }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $result['title'] }}</p>
                            @if($result['excerpt'])
                            <p class="text-xs text-gray-600 line-clamp-2 mt-1">{{ $result['excerpt'] }}</p>
                            @endif
                            @if($result['date'])
                            <p class="text-xs text-gray-400 mt-1">{{ $result['date'] }}</p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @elseif($query && strlen($query) >= 2)
            <div class="p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-gray-600">Tidak ada hasil untuk "{{ $query }}"</p>
            </div>
        @endif

        @if($query && strlen($query) >= 2 && count($results) > 0)
        <div class="border-t border-gray-200 p-3 bg-gray-50">
            <a href="{{ route('news.index', ['q' => $query]) }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                Lihat semua hasil →
            </a>
        </div>
        @endif
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="absolute right-12 top-2.5">
        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
</div>
