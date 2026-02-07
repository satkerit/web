<x-frontend-layout>
    <x-slot name="title">Brosur Pembiayaan Syariah - BPRS Bangka Belitung</x-slot>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4 tracking-tight">Brosur Pembiayaan Syariah</h1>
            <p class="text-lg text-emerald-50 max-w-2xl mx-auto">
                Download informasi lengkap mengenai produk pembiayaan syariah kami.
            </p>
        </div>
    </section>

    <!-- Brochure List -->
    <section class="py-16 bg-slate-50 min-h-[500px]" x-data="{ showPreview: false, previewUrl: '', previewTitle: '', isLoading: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($brochures as $brochure)
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-6">
                                <div class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-xl flex items-center justify-center text-red-500 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">
                                    {{ number_format($brochure->file_size / 1024, 2) }} KB
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-slate-900 mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors" title="{{ $brochure->original_name }}">
                                {{ $brochure->original_name }}
                            </h3>
                            <p class="text-sm text-slate-500 mb-6 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $brochure->created_at->format('d M Y') }}
                            </p>

                            <div class="flex gap-3">
                                <a href="{{ route('brochures.download', $brochure) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-emerald-600/30 transition-all duration-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>
                                <button
                                    @click="showPreview = true; isLoading = true; previewUrl = '{{ route('brochures.preview', $brochure) }}'; previewTitle = '{{ $brochure->original_name }}'"
                                    class="px-4 py-2.5 text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition-colors font-medium"
                                    title="Preview"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Belum ada brosur tersedia</h3>
                        <p class="text-slate-500">Silakan kembali lagi nanti untuk melihat brosur terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- PDF Preview Modal -->
        <div
            x-show="showPreview"
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div
                    x-show="showPreview"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm"
                    @click="showPreview = false"
                    aria-hidden="true"
                ></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    x-show="showPreview"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl leading-6 font-bold text-gray-900" x-text="previewTitle">Preview</h3>
                            <button @click="showPreview = false" type="button" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="w-full h-[75vh] bg-slate-100 rounded-xl overflow-hidden relative">
                            <!-- Loading Spinner -->
                            <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-slate-50 z-10">
                                <svg class="animate-spin h-10 w-10 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <iframe
                                :src="previewUrl"
                                class="w-full h-full border-0 relative z-0"
                                @load="isLoading = false"
                            ></iframe>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <a :href="previewUrl" download class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-emerald-600/20 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-base font-semibold text-white hover:shadow-emerald-600/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-300">
                            Download
                        </a>
                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-200 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors" @click="showPreview = false">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-frontend-layout>
