<x-frontend-layout>
    <x-slot name="title">{{ $news->title }} - BPRS Bangka Belitung</x-slot>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.15&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm mb-6">
                <a href="{{ route('home') }}" class="text-white/70 hover:text-white transition-colors">Beranda</a>
                <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('news.index') }}" class="text-white/70 hover:text-white transition-colors">Berita</a>
                <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white font-medium">Detail</span>
            </nav>

            @if($news->category)
            <span class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-medium mb-4">
                {{ $news->category }}
            </span>
            @endif

            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">{{ $news->title }}</h1>
            <div class="flex items-center text-white/80 space-x-4">
                <time datetime="{{ $news->published_at->toISOString() }}">
                    {{ $news->published_at->translatedFormat('d F Y') }}
                </time>
                @if($news->author)
                <span>•</span>
                <span>{{ $news->author }}</span>
                @endif
            </div>
        </div>
    </section>

    <article class="py-12 -mt-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl p-6 md:p-10">

            <!-- Slideshow -->
            @php
                $slides = collect([]);
                if($news->featured_image) $slides->push(Storage::url($news->featured_image));
                foreach($news->images as $img) $slides->push(Storage::url($img->image_path));
            @endphp

            @if($slides->count() > 0)
            <div x-data="{
                activeSlide: 0,
                slides: {{ json_encode($slides) }},
                interval: null,
                next() { this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1 },
                prev() { this.activeSlide = (this.activeSlide === 0) ? this.slides.length - 1 : this.activeSlide - 1 },
                goTo(index) { this.activeSlide = index },
                startAuto() { this.interval = setInterval(() => this.next(), 5000) },
                stopAuto() { clearInterval(this.interval) }
            }"
            x-init="startAuto()"
            @mouseenter="stopAuto()"
            @mouseleave="startAuto()"
            class="relative w-full mb-8 group rounded-2xl overflow-hidden shadow-lg bg-gray-100 aspect-video">

                <!-- Slides -->
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index"
                         x-transition:enter="transition transform duration-500 ease-in-out"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition transform duration-500 ease-in-out"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute inset-0 w-full h-full">
                        <img :src="slide" class="w-full h-full object-cover" :alt="'Slide ' + (index + 1)">
                    </div>
                </template>

                <!-- Navigation Arrows -->
                <div x-show="slides.length > 1">
                    <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 backdrop-blur-sm text-white p-2 rounded-full transition-all opacity-0 group-hover:opacity-100 focus:opacity-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 backdrop-blur-sm text-white p-2 rounded-full transition-all opacity-0 group-hover:opacity-100 focus:opacity-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <!-- Indicators -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2" x-show="slides.length > 1">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="goTo(index)"
                                :class="activeSlide === index ? 'w-8 bg-emerald-500' : 'w-2 bg-white/50 hover:bg-white'"
                                class="h-2 rounded-full transition-all duration-300 shadow-sm"></button>
                    </template>
                </div>
            </div>
            @endif

            <!-- Content -->
            <div class="prose prose-emerald prose-lg max-w-none">
                @cleanHtml($news->content)
            </div>

            <!-- Share -->
            <div class="mt-12 pt-8 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Bagikan Artikel</h3>
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-10 h-10 bg-gray-100 hover:bg-emerald-100 rounded-lg flex items-center justify-center text-gray-500 hover:text-emerald-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}" target="_blank" class="w-10 h-10 bg-gray-100 hover:bg-emerald-100 rounded-lg flex items-center justify-center text-gray-500 hover:text-emerald-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}" target="_blank" class="w-10 h-10 bg-gray-100 hover:bg-emerald-100 rounded-lg flex items-center justify-center text-gray-500 hover:text-emerald-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                </div>
            </div>
            </div>

            <!-- Back -->
            <div class="mt-8">
                <a href="{{ route('news.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-semibold transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Berita
                </a>
            </div>
        </div>
    </article>
</x-frontend-layout>
