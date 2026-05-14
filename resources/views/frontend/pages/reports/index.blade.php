<x-frontend-layout>
    <x-slot name="title">{{ $title }} - BPRS Bangka Belitung</x-slot>

    <!-- Hero -->
    <section class="relative pt-24 sm:pt-28 md:pt-32 pb-12 sm:pb-16 md:pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.03&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-3 sm:mb-4 tracking-tight px-4">{{ $title }}</h1>
            <p class="text-base sm:text-lg text-white/80 px-4">{{ $subtitle }}</p>
        </div>
    </section>

    <section class="py-12 sm:py-14 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($years->count() > 0)
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl shadow-gray-200/50 p-4 sm:p-6 mb-6 sm:mb-8 flex flex-wrap items-center gap-2 border border-gray-100">
                <span class="text-sm sm:text-base text-gray-700 font-semibold mr-1 sm:mr-2 font-heading w-full sm:w-auto mb-2 sm:mb-0">Filter Tahun:</span>
                <a href="{{ request()->url() }}" class="px-3 sm:px-5 py-1.5 sm:py-2 rounded-full font-medium text-xs sm:text-sm transition-all duration-300 touch-manipulation active:scale-95 {{ !request('year') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg shadow-emerald-600/30 transform scale-105' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900' }}">Semua</a>
                @foreach($years as $year)
                <a href="{{ request()->fullUrlWithQuery(['year' => $year]) }}" class="px-3 sm:px-5 py-1.5 sm:py-2 rounded-full font-medium text-xs sm:text-sm transition-all duration-300 touch-manipulation active:scale-95 {{ request('year') == $year ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg shadow-emerald-600/30 transform scale-105' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900' }}">{{ $year }}</a>
                @endforeach
            </div>
            @endif

            @if($reports->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                @foreach($reports as $report)
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group touch-manipulation active:scale-[0.99]">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-start justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-50 rounded-lg sm:rounded-xl flex items-center justify-center text-red-600 flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </div>
                            <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap justify-end">
                                <span class="text-[10px] sm:text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded-full border border-emerald-100">{{ $report->year }}</span>
                                @if($type === 'keuangan_publikasi')
                                <span class="text-[10px] sm:text-xs font-semibold text-blue-700 bg-blue-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded-full border border-blue-100">{{ $report->quarter ? 'Q'.$report->quarter : 'Tahunan' }}</span>
                                @endif
                            </div>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors leading-tight">{{ $report->title }}</h3>
                        @if($report->description)
                        <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4 line-clamp-2">{{ Str::limit($report->description, 90) }}</p>
                        @endif
                        <div class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm text-gray-600">
                            <p class="flex items-center">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $report->posted_at ? $report->posted_at->format('d M Y') : '-' }}
                            </p>
                            <p class="flex items-center">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ $report->file_size ? number_format($report->file_size/1024/1024, 2).' MB' : '-' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 mt-3 sm:mt-4">
                            <span class="flex items-center text-blue-600 bg-blue-50 px-1.5 sm:px-2 py-1 rounded-full text-[10px] sm:text-xs font-medium">
                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <span id="preview-{{ $report->id }}">{{ number_format($report->preview_count ?? 0) }}</span>
                            </span>
                            <span class="flex items-center text-green-600 bg-green-50 px-1.5 sm:px-2 py-1 rounded-full text-[10px] sm:text-xs font-medium">
                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span id="download-{{ $report->id }}">{{ number_format($report->download_count ?? 0) }}</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-4 sm:mt-6">
                            <button type="button" onclick="openPreview('{{ route('reports.preview', $report->id) }}', '{{ e($report->title) }}', {{ $report->id }})" class="flex-1 min-h-[44px] px-3 sm:px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs sm:text-sm rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200 flex items-center justify-center shadow-sm touch-manipulation active:scale-95">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Preview
                            </button>
                            <a href="{{ route('reports.download', $report->id) }}" onclick="incrementDownload({{ $report->id }})" class="flex-1 min-h-[44px] px-3 sm:px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-xs sm:text-sm rounded-lg hover:shadow-lg hover:shadow-emerald-600/30 transition-all duration-200 flex items-center justify-center shadow-md touch-manipulation active:scale-95">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-6 sm:mt-8">{{ $reports->links() }}</div>
            @else
            <div class="text-center py-12 sm:py-16 text-sm sm:text-base text-gray-500">Belum ada laporan tersedia</div>
            @endif
        </div>
    </section>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-[99999] hidden backdrop-blur-sm" style="background: rgba(0,0,0,0.85);">
        <div class="flex flex-col h-full p-4 sm:p-6">
            <div class="bg-gray-900 rounded-t-2xl flex items-center justify-between px-6 py-4 text-white shadow-2xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white/10 rounded-lg mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <h3 id="previewTitle" class="text-lg font-bold font-heading truncate max-w-xl">Preview</h3>
                </div>
                <div class="flex items-center gap-3">
                    <a id="previewDownload" href="#" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-500 hover:shadow-lg hover:shadow-emerald-600/30 transition-all duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download
                    </a>
                    <button onclick="closePreview()" class="p-2 hover:bg-gray-700 rounded-lg transition-colors text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            <div class="flex-1 overflow-hidden bg-gray-100 rounded-b-2xl shadow-2xl relative">
                <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                    <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <iframe id="previewFrame" src="" class="w-full h-full border-0 relative z-10 bg-white"></iframe>
            </div>
        </div>
    </div>

    @push('scripts')
    <script nonce="{{ $nonce }}">
    var currentReportId = null;

    function openPreview(url, title, id) {
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('previewDownload').href = url.replace('/preview/', '/download/');
        document.getElementById('previewFrame').src = url;
        document.getElementById('previewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        currentReportId = id;
        var el = document.getElementById('preview-' + id);
        if (el) el.textContent = (parseInt(el.textContent.replace(/,/g,'')) + 1).toLocaleString();
    }

    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
        document.getElementById('previewFrame').src = '';
        document.body.style.overflow = '';
    }

    function incrementDownload(id) {
        var el = document.getElementById('download-' + id);
        if (el) el.textContent = (parseInt(el.textContent.replace(/,/g,'')) + 1).toLocaleString();
    }

    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closePreview(); });
    </script>
    @endpush
</x-frontend-layout>
