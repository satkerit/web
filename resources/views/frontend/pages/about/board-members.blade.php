<x-frontend-layout>
    <x-slot name="title">{{ $title }} - BPRS Bangka Belitung</x-slot>

    <!-- Hero -->
    <section class="relative py-12 sm:py-16 md:py-20 lg:py-24 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-xs sm:text-sm font-medium mb-4 sm:mb-6 ring-1 ring-white/20">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Tentang Kami
            </span>
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 sm:mb-6 tracking-tight px-4">{{ $title }}</h1>
            <p class="text-base sm:text-lg md:text-xl text-emerald-50 max-w-2xl mx-auto px-4">{{ $subtitle }}</p>
        </div>
    </section>

    <section class="py-12 sm:py-16 md:py-20 lg:py-24 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($members->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
                @foreach($members as $member)
                <div class="group relative flex flex-col bg-white rounded-2xl sm:rounded-[2rem] shadow-sm border border-gray-100/80 overflow-hidden hover:shadow-xl hover:shadow-teal-900/5 hover:-translate-y-1.5 transition-all duration-500 ease-out touch-manipulation active:scale-[0.99]"
                     x-data="{ loaded: false }">
                    
                    <!-- Image Aspect Ratio 4:5 -->
                    <div class="relative aspect-[4/5] overflow-hidden bg-gray-100">
                        <!-- Skeleton Loader -->
                        <div x-show="!loaded" class="absolute inset-0 animate-pulse bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200"></div>
                        
                        @if($member->photo)
                        <img src="{{ \App\Helpers\StorageHelper::url($member->photo) }}" 
                             alt="{{ $member->name }}" 
                             loading="lazy"
                             x-init="if($el.complete) loaded = true"
                             @load="loaded = true"
                             class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-110"
                             :class="loaded ? 'opacity-100' : 'opacity-0'">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-teal-50 to-emerald-50 flex items-center justify-center" x-init="loaded = true">
                            <div class="text-center p-4 sm:p-6">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3 text-teal-600">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="text-[10px] sm:text-xs font-medium text-teal-600 uppercase tracking-widest">No Photo</span>
                            </div>
                        </div>
                        @endif

                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-teal-950/90 via-teal-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Hover Quick Info -->
                        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out">
                            <div class="flex items-center gap-2 text-white/90 text-[10px] sm:text-xs font-medium uppercase tracking-widest mb-1.5 sm:mb-2">
                                <span class="w-6 sm:w-8 h-px bg-teal-400"></span>
                                Profile Details
                            </div>
                            <p class="text-white/80 text-xs sm:text-sm line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($member->biography ?? 'Anggota ' . $title), 80) }}
                            </p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4 sm:p-6 text-center">
                        <div class="mb-3 sm:mb-4">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-teal-600 transition-colors duration-300 leading-snug mb-1.5 sm:mb-1 line-clamp-2">
                                {{ $member->name }}
                            </h3>
                            <div class="inline-flex items-center px-2.5 sm:px-3 py-1 bg-teal-50 rounded-full text-teal-700 text-[10px] sm:text-xs font-bold uppercase tracking-wider">
                                {{ $member->position }}
                            </div>
                        </div>

                        <button
                            x-data
                            @click="$dispatch('open-modal', { member: {{ json_encode($member) }} })"
                            class="inline-flex items-center justify-center w-full min-h-[44px] px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-900 text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl hover:bg-teal-600 hover:shadow-lg hover:shadow-teal-600/20 active:scale-95 transition-all duration-300 touch-manipulation"
                        >
                            <span>Selengkapnya</span>
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 ml-1.5 sm:ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada data tersedia</h3>
                <p class="text-gray-500">Data pengurus belum ditambahkan.</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Modal -->
    <div
        x-data="{ open: false, member: null }"
        @open-modal.window="open = true; member = $event.detail.member; document.body.style.overflow = 'hidden'"
        x-init="$watch('open', value => { if(!value) document.body.style.overflow = '' })"
        x-show="open"
        x-cloak
        class="relative z-50"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
            @click="open = false"
        ></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    x-show="open"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-xl sm:rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-gray-100 max-h-[90vh] overflow-y-auto"
                    @click.stop
                >
                    <div class="absolute right-3 top-3 sm:right-4 sm:top-4 z-10">
                        <button
                            @click="open = false"
                            type="button"
                            class="rounded-full bg-white/80 p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition-all duration-200 touch-manipulation active:scale-95"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="flex flex-col sm:flex-row gap-6 sm:gap-8">
                            <div class="flex-shrink-0 mx-auto sm:mx-0">
                                <template x-if="member && member.photo">
                                    <img :src="'/storage/' + member.photo" :alt="member.name" class="w-40 h-52 sm:w-48 sm:h-64 object-cover rounded-lg sm:rounded-xl shadow-md">
                                </template>
                                <template x-if="!member || !member.photo">
                                    <div class="w-40 h-52 sm:w-48 sm:h-64 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                </template>
                            </div>

                            <div class="flex-1 mt-2 sm:mt-0 text-left">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight" x-text="member?.name"></h3>
                                <p class="text-emerald-600 font-semibold text-base sm:text-lg mb-4 sm:mb-6" x-text="member?.position"></p>

                                <div class="prose prose-sm prose-emerald max-w-none text-gray-600">
                                    <template x-if="member && member.biography">
                                        <p x-html="member.biography.replace(/\n/g, '<br>')" class="whitespace-pre-line leading-relaxed text-sm sm:text-base"></p>
                                    </template>
                                </div>

                                <template x-if="member && member.education && member.education.length > 0">
                                    <div class="mt-6 sm:mt-8 bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-5 border border-gray-100">
                                        <h4 class="font-bold text-gray-900 mb-2 sm:mb-3 flex items-center text-sm sm:text-base">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                                            Riwayat Pendidikan
                                        </h4>
                                        <ul class="space-y-1.5 sm:space-y-2">
                                            <template x-for="edu in member.education" :key="edu">
                                                <li class="flex items-start text-xs sm:text-sm text-gray-600">
                                                    <span class="mr-2 mt-1.5 w-1.5 h-1.5 bg-emerald-400 rounded-full flex-shrink-0"></span>
                                                    <span x-text="edu"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </template>

                                <template x-if="member && member.experience && member.experience.length > 0">
                                    <div class="mt-3 sm:mt-4 bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-5 border border-gray-100">
                                        <h4 class="font-bold text-gray-900 mb-2 sm:mb-3 flex items-center text-sm sm:text-base">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            Pengalaman Kerja
                                        </h4>
                                        <ul class="space-y-1.5 sm:space-y-2">
                                            <template x-for="exp in member.experience" :key="exp">
                                                <li class="flex items-start text-xs sm:text-sm text-gray-600">
                                                    <span class="mr-2 mt-1.5 w-1.5 h-1.5 bg-emerald-400 rounded-full flex-shrink-0"></span>
                                                    <span x-text="exp"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100 rounded-b-xl sm:rounded-b-2xl">
                        <button
                            type="button"
                            class="inline-flex w-full justify-center min-h-[48px] rounded-lg sm:rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors touch-manipulation active:scale-95"
                            @click="open = false"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>
