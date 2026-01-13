<x-frontend-layout>
    <x-slot name="title">{{ $title }} - BPRS Bangka Belitung</x-slot>

    <!-- Hero -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-700 via-teal-600 to-emerald-800">
            <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><circle cx=%2250%22 cy=%2250%22 r=%2240%22 fill=%22none%22 stroke=%22white%22 stroke-width=%220.5%22/></svg>'); background-size: 50px;"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">{{ $title }}</h1>
            <p class="text-lg text-white/80">{{ $subtitle }}</p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($years->count() > 0)
            <div class="mb-8 flex flex-wrap gap-2">
                <span class="text-gray-600 mr-2 py-2">Filter Tahun:</span>
                <a href="{{ request()->url() }}" class="px-4 py-2 rounded-lg font-medium transition {{ !request('year') ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Semua</a>
                @foreach($years as $year)
                <a href="{{ request()->fullUrlWithQuery(['year' => $year]) }}" class="px-4 py-2 rounded-lg font-medium transition {{ request('year') == $year ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ $year }}</a>
                @endforeach
            </div>
            @endif

            @if($reports->count() > 0)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                            @if($type === 'keuangan_publikasi')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Publish</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukuran</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Statistik</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-red-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $report->title }}</p>
                                        @if($report->description)
                                        <p class="text-sm text-gray-500">{{ Str::limit($report->description, 50) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $report->year }}</td>
                            @if($type === 'keuangan_publikasi')
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $report->quarter ? 'Q'.$report->quarter : 'Tahunan' }}</td>
                            @endif
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $report->posted_at ? $report->posted_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $report->file_size ? number_format($report->file_size/1024/1024, 2).' MB' : '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-4 text-sm">
                                    <span class="text-blue-600" title="Dilihat"><span id="preview-{{ $report->id }}">{{ number_format($report->preview_count ?? 0) }}</span> views</span>
                                    <span class="text-green-600" title="Diunduh"><span id="download-{{ $report->id }}">{{ number_format($report->download_count ?? 0) }}</span> unduh</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="openPreview('{{ route('reports.preview', $report->id) }}', '{{ e($report->title) }}', {{ $report->id }})" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Preview</button>
                                    <a href="{{ route('reports.download', $report->id) }}" onclick="incrementDownload({{ $report->id }})" class="px-3 py-1.5 bg-green-600 text-white text-sm rounded hover:bg-green-700">Download</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $reports->links() }}</div>
            @else
            <div class="text-center py-12 text-gray-500">Belum ada laporan tersedia</div>
            @endif
        </div>
    </section>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-[99999] hidden" style="background: rgba(0,0,0,0.95);">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between px-4 py-3 bg-gray-800 text-white">
                <h3 id="previewTitle" class="text-lg font-semibold truncate">Preview</h3>
                <div class="flex items-center gap-3">
                    <a id="previewDownload" href="#" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Download</a>
                    <button onclick="closePreview()" class="p-2 hover:bg-gray-700 rounded">&times;</button>
                </div>
            </div>
            <div class="flex-1 overflow-hidden">
                <iframe id="previewFrame" src="" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
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
