<x-frontend-layout>
    <x-slot name="title">{{ $news->title }} - BPRS Bangka Belitung</x-slot>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-700 via-primary-500 to-primary-600 py-16 md:py-20">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
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

            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-4 leading-tight">{{ $news->title }}</h1>
            <div class="flex flex-wrap items-center text-white/80 gap-3 text-sm md:text-base">
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

    <!-- Main Content -->
    <article class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 lg:p-10">

            <!-- Image Slideshow -->
            @php
                $slides = collect([]);
                if($news->featured_image) $slides->push(\App\Helpers\StorageHelper::url($news->featured_image));
                foreach($news->images as $img) $slides->push(\App\Helpers\StorageHelper::url($img->image_path));
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
            class="relative w-full mb-8 group rounded-xl overflow-hidden shadow-lg bg-gray-100 aspect-video">

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
                        <img :src="slide" class="w-full h-full object-cover" :alt="'Slide ' + (index + 1)" loading="lazy">
                    </div>
                </template>

                <!-- Navigation Arrows -->
                <div x-show="slides.length > 1">
                    <button @click="prev()" class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-white/90 hover:bg-white backdrop-blur-sm text-gray-800 rounded-full transition-all opacity-0 group-hover:opacity-100 focus:opacity-100 shadow-lg flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 w-10 h-10 md:w-12 md:h-12 bg-white/90 hover:bg-white backdrop-blur-sm text-gray-800 rounded-full transition-all opacity-0 group-hover:opacity-100 focus:opacity-100 shadow-lg flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <!-- Indicators -->
                <div class="absolute bottom-3 md:bottom-4 left-1/2 -translate-x-1/2 flex space-x-2" x-show="slides.length > 1">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="goTo(index)"
                                :class="activeSlide === index ? 'w-6 md:w-8 bg-primary-500' : 'w-2 bg-white/50 hover:bg-white'"
                                class="h-2 rounded-full transition-all duration-300 shadow-sm"></button>
                    </template>
                </div>

                <!-- Counter -->
                <div class="absolute top-3 md:top-4 right-3 md:right-4 bg-black/60 text-white px-3 md:px-4 py-1.5 md:py-2 rounded-full text-xs md:text-sm font-medium backdrop-blur-sm" x-show="slides.length > 1">
                    <span x-text="activeSlide + 1"></span> / <span x-text="slides.length"></span>
                </div>
            </div>
            @endif

            <!-- Content -->
            <div class="prose prose-primary prose-base md:prose-lg max-w-none">
                <!-- Custom prose styling for better readability -->
                <style>
                    .prose-primary {
                        --tw-prose-body: rgb(55 65 81);
                        --tw-prose-headings: rgb(17 24 39);
                        --tw-prose-links: rgb(13 148 136);
                        --tw-prose-bold: rgb(17 24 39);
                        --tw-prose-counters: rgb(107 114 128);
                        --tw-prose-bullets: rgb(209 213 219);
                        --tw-prose-hr: rgb(229 231 235);
                        --tw-prose-quotes: rgb(17 24 39);
                        --tw-prose-quote-borders: rgb(209 213 219);
                        --tw-prose-captions: rgb(107 114 128);
                        --tw-prose-code: rgb(17 24 39);
                        --tw-prose-pre-code: rgb(229 231 235);
                        --tw-prose-pre-bg: rgb(31 41 55);
                        --tw-prose-th-borders: rgb(209 213 219);
                        --tw-prose-td-borders: rgb(229 231 235);
                    }
                    .prose-primary img {
                        border-radius: 0.75rem;
                        margin-top: 2rem;
                        margin-bottom: 2rem;
                    }
                    .prose-primary h2 {
                        margin-top: 2.5rem;
                        margin-bottom: 1.25rem;
                    }
                    .prose-primary h3 {
                        margin-top: 2rem;
                        margin-bottom: 1rem;
                    }
                    .prose-primary p {
                        margin-top: 1.25rem;
                        margin-bottom: 1.25rem;
                        line-height: 1.75;
                    }
                    .prose-primary ul, .prose-primary ol {
                        margin-top: 1.25rem;
                        margin-bottom: 1.25rem;
                    }
                    .prose-primary li {
                        margin-top: 0.5rem;
                        margin-bottom: 0.5rem;
                    }
                </style>
                @cleanHtml($news->content)
            </div>

            <!-- Tags (if available) -->
            @if($news->tags && count($news->tags) > 0)
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($news->tags as $tag)
                    <span class="px-3 py-1.5 bg-primary-50 text-primary-700 text-sm font-medium rounded-lg">
                        #{{ $tag }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Share -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Bagikan Artikel</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-primary-100 rounded-lg text-gray-700 hover:text-primary-600 transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                        <span class="hidden sm:inline">Facebook</span>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-primary-100 rounded-lg text-gray-700 hover:text-primary-600 transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        <span class="hidden sm:inline">Twitter</span>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-primary-100 rounded-lg text-gray-700 hover:text-primary-600 transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <span class="hidden sm:inline">WhatsApp</span>
                    </a>
                    <button @click="navigator.clipboard.writeText('{{ request()->url() }}'); $dispatch('notify', {message: 'Link berhasil disalin!'})" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-primary-100 rounded-lg text-gray-700 hover:text-primary-600 transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span class="hidden sm:inline">Salin Link</span>
                    </button>
                </div>
            </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6 md:mt-8">
                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold transition-colors text-sm md:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Berita
                </a>
            </div>
        </div>
    </article>
</x-frontend-layout>
