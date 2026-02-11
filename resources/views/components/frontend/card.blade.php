@props(['title', 'subtitle' => null, 'image' => null, 'href' => '#', 'delay' => 0])

<div class="group bg-white rounded-2xl shadow-xl shadow-gray-200/50 overflow-hidden hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-300 transform hover:-translate-y-2 border border-gray-100"
     data-aos="fade-up" data-aos-delay="{{ $delay }}">
    
    @if($image)
    <div class="relative h-48 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10 opacity-60 group-hover:opacity-40 transition-opacity"></div>
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" loading="lazy">
        <div class="absolute bottom-4 left-4 z-20">
            <span class="px-3 py-1 bg-emerald-500/90 backdrop-blur-sm text-white text-xs font-semibold rounded-lg shadow-lg">
                {{ $subtitle }}
            </span>
        </div>
    </div>
    @endif

    <div class="p-6">
        @if(!$image && $subtitle)
            <div class="text-sm text-emerald-600 font-semibold mb-2 uppercase tracking-wider">{{ $subtitle }}</div>
        @endif
        
        <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-emerald-600 transition-colors line-clamp-2">
            <a href="{{ $href }}">
                {{ $title }}
            </a>
        </h3>
        
        <div class="text-gray-600 mb-4 line-clamp-3 text-sm leading-relaxed">
            {{ $slot }}
        </div>

        <div class="pt-4 border-t border-gray-50 flex justify-between items-center">
            <a href="{{ $href }}" class="inline-flex items-center text-emerald-600 font-semibold hover:text-emerald-700 transition-colors group/link">
                Selengkapnya
                <svg class="w-4 h-4 ml-1 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>
