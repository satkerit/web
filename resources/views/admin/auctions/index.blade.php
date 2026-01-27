<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Lelang Agunan') }}
            </h2>
            <a href="{{ route('admin.auctions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Tambah Lelang Agunan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Cari judul, nomor lelang agunan, alamat..."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Semua Status</option>
                                    @foreach(\App\Models\Auction::$statusLabels as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Aset</label>
                                <select name="asset_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Semua Jenis</option>
                                    @foreach(\App\Models\Auction::$assetTypes as $value => $label)
                                        <option value="{{ $value }}" {{ request('asset_type') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                                <input type="text" name="city" value="{{ request('city') }}" 
                                       placeholder="Nama kota..."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="md:col-span-4 flex gap-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Filter
                                </button>
                                <a href="{{ route('admin.auctions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="mb-4">
                        <form id="bulk-form" method="POST" action="{{ route('admin.auctions.bulk-action') }}">
                            @csrf
                            <div class="flex items-center gap-4">
                                <select name="action" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Aksi</option>
                                    <option value="publish">Publikasi</option>
                                    <option value="unpublish">Unpublish</option>
                                    <option value="feature">Feature</option>
                                    <option value="unfeature">Unfeature</option>
                                    <option value="delete">Hapus</option>
                                </select>
                                <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded" 
                                        onclick="return confirm('Yakin ingin melakukan aksi ini?')">
                                    Jalankan
                                </button>
                                <span class="text-sm text-gray-600">
                                    <span id="selected-count">0</span> item dipilih
                                </span>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="select-all" class="rounded border-gray-300">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lelang Agunan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis & Lokasi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga Limit
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Lelang Agunan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($auctions as $auction)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $auction->id }}" 
                                                   class="item-checkbox rounded border-gray-300" form="bulk-form">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($auction->main_image)
                                                    <img src="{{ $auction->main_image }}" alt="{{ $auction->title }}" 
                                                         class="h-12 w-12 rounded-lg object-cover mr-4">
                                                @else
                                                    <div class="h-12 w-12 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $auction->title }}</div>
                                                    <div class="text-sm text-gray-500">{{ $auction->auction_number }}</div>
                                                    @if($auction->is_featured)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Featured
                                                        </span>
                                                    @endif
                                                    @if($auction->is_urgent)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Urgent
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $auction->asset_type_label }}</div>
                                            <div class="text-sm text-gray-500">{{ $auction->city }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $auction->formatted_limit_price }}</div>
                                            @if($auction->estimated_price)
                                                <div class="text-sm text-gray-500">Taksiran: {{ $auction->formatted_estimated_price }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($auction->auction_date)
                                                <div class="text-sm text-gray-900">{{ $auction->auction_date->format('d/m/Y') }}</div>
                                                <div class="text-sm text-gray-500">{{ $auction->auction_date->format('H:i') }} WIB</div>
                                            @else
                                                <div class="text-sm text-gray-500">Belum ditentukan</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $auction->status_color['bg'] }} {{ $auction->status_color['text'] }}">
                                                <span class="w-1.5 h-1.5 mr-1.5 {{ $auction->status_color['dot'] }} rounded-full"></span>
                                                {{ $auction->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.auctions.show', $auction) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                                                <a href="{{ route('admin.auctions.edit', $auction) }}" 
                                                   class="text-green-600 hover:text-green-900">Edit</a>
                                                <form method="POST" action="{{ route('admin.auctions.destroy', $auction) }}" 
                                                      class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data lelang agunan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $auctions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Bulk selection
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });

        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        function updateSelectedCount() {
            const selected = document.querySelectorAll('.item-checkbox:checked').length;
            document.getElementById('selected-count').textContent = selected;
        }
    </script>
    @endpush
</x-app-layout>