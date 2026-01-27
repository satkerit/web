<x-admin-auction-layout>
    <x-slot name="header">Kelola Lelang Agunan</x-slot>
    <x-slot name="subtitle">Daftar semua lelang agunan yang tersedia</x-slot>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 animate-fade-in-up">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 animate-fade-in-up">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Header Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Daftar Lelang Agunan</h2>
            <p class="text-gray-600 mt-2">Kelola semua lelang agunan dari satu tempat</p>
        </div>
        <a href="{{ route('admin.auctions.create') }}" 
           class="btn-auction-admin-primary inline-flex items-center space-x-2 px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Tambah Lelang Agunan</span>
        </a>
    </div>

    <!-- Filters Card -->
    <div class="admin-auction-card p-6 mb-8 animate-slide-in-left">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
            </svg>
            Filter & Pencarian
        </h3>
        
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari judul, nomor lelang, alamat..."
                       class="admin-auction-input w-full">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="admin-auction-input w-full">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Auction::$statusLabels as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Aset</label>
                <select name="asset_type" class="admin-auction-input w-full">
                    <option value="">Semua Jenis</option>
                    @foreach(\App\Models\Auction::$assetTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('asset_type') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kota</label>
                <input type="text" name="city" value="{{ request('city') }}" 
                       placeholder="Nama kota..."
                       class="admin-auction-input w-full">
            </div>
            <div class="lg:col-span-4 flex gap-3 pt-4">
                <button type="submit" class="btn-auction-admin-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.auctions.index') }}" class="btn-auction-admin-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Card -->
    <div class="admin-auction-card p-6 mb-8 animate-slide-in-right">
        <form id="bulk-form" method="POST" action="{{ route('admin.auctions.bulk-action') }}">
            @csrf
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <select name="action" class="admin-auction-input">
                        <option value="">Pilih Aksi Massal</option>
                        <option value="publish">📢 Publikasi</option>
                        <option value="unpublish">📝 Unpublish</option>
                        <option value="feature">⭐ Feature</option>
                        <option value="unfeature">⚪ Unfeature</option>
                        <option value="delete">🗑️ Hapus</option>
                    </select>
                    <button type="submit" class="btn-auction-admin-primary" 
                            onclick="return confirm('Yakin ingin melakukan aksi ini pada item yang dipilih?')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Jalankan
                    </button>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="selected-count">0</span> item dipilih
                </div>
            </div>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="admin-auction-card animate-fade-in-up">
        <div class="admin-auction-table">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="text-left">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        </th>
                        <th class="text-left">Lelang Agunan</th>
                        <th class="text-left">Jenis & Lokasi</th>
                        <th class="text-left">Harga Limit</th>
                        <th class="text-left">Tanggal Lelang</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auctions as $auction)
                        <tr class="hover:bg-orange-50/50 transition-colors">
                            <td>
                                <input type="checkbox" name="selected_ids[]" value="{{ $auction->id }}" 
                                       class="item-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500" form="bulk-form">
                            </td>
                            <td>
                                <div class="flex items-center space-x-4">
                                    @if($auction->main_image)
                                        <img src="{{ $auction->main_image }}" alt="{{ $auction->title }}" 
                                             class="h-16 w-16 rounded-xl object-cover shadow-md">
                                    @else
                                        <div class="h-16 w-16 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center shadow-md">
                                            <svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-gray-900 text-sm">{{ Str::limit($auction->title, 40) }}</div>
                                        <div class="text-xs text-gray-500 font-mono">{{ $auction->auction_number }}</div>
                                        <div class="flex items-center space-x-2 mt-1">
                                            @if($auction->is_featured)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⭐ Featured
                                                </span>
                                            @endif
                                            @if($auction->is_urgent)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    🔥 Urgent
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm font-medium text-gray-900">{{ $auction->asset_type_label }}</div>
                                <div class="text-xs text-gray-500 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $auction->city ?? 'Tidak ada' }}
                                </div>
                            </td>
                            <td>
                                <div class="font-bold text-orange-600">{{ $auction->formatted_limit_price }}</div>
                                @if($auction->estimated_price)
                                    <div class="text-xs text-gray-500">Taksiran: {{ $auction->formatted_estimated_price }}</div>
                                @endif
                            </td>
                            <td>
                                @if($auction->auction_date)
                                    <div class="text-sm font-medium text-gray-900">{{ $auction->auction_date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $auction->auction_date->format('H:i') }} WIB</div>
                                    <div class="text-xs text-orange-600 font-medium">{{ $auction->time_until_auction }}</div>
                                @else
                                    <div class="text-sm text-gray-500">Belum ditentukan</div>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ $auction->status }}">
                                    {{ $auction->status_label }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.auctions.show', $auction) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors"
                                       title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.auctions.edit', $auction) }}" 
                                       class="text-green-600 hover:text-green-800 font-medium text-sm transition-colors"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.auctions.destroy', $auction) }}" 
                                          class="inline" onsubmit="return confirm('Yakin ingin menghapus lelang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors"
                                                title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada lelang agunan</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan lelang agunan pertama Anda.</p>
                                    <a href="{{ route('admin.auctions.create') }}" class="btn-auction-admin-primary">
                                        Tambah Lelang Agunan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($auctions->hasPages())
            <div class="p-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $auctions->firstItem() }} - {{ $auctions->lastItem() }} dari {{ $auctions->total() }} lelang
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $auctions->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Enhanced bulk selection with animations
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach((checkbox, index) => {
                setTimeout(() => {
                    checkbox.checked = this.checked;
                    if (this.checked) {
                        checkbox.closest('tr').classList.add('bg-orange-50');
                    } else {
                        checkbox.closest('tr').classList.remove('bg-orange-50');
                    }
                }, index * 50);
            });
            updateSelectedCount();
        });

        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.closest('tr').classList.add('bg-orange-50');
                } else {
                    this.closest('tr').classList.remove('bg-orange-50');
                }
                updateSelectedCount();
            });
        });

        function updateSelectedCount() {
            const selected = document.querySelectorAll('.item-checkbox:checked').length;
            document.getElementById('selected-count').textContent = selected;
            
            // Update select-all checkbox state
            const selectAll = document.getElementById('select-all');
            const total = document.querySelectorAll('.item-checkbox').length;
            
            if (selected === 0) {
                selectAll.indeterminate = false;
                selectAll.checked = false;
            } else if (selected === total) {
                selectAll.indeterminate = false;
                selectAll.checked = true;
            } else {
                selectAll.indeterminate = true;
            }
        }

        // Enhanced form validation
        document.getElementById('bulk-form').addEventListener('submit', function(e) {
            const selected = document.querySelectorAll('.item-checkbox:checked').length;
            const action = this.querySelector('select[name="action"]').value;
            
            if (selected === 0) {
                e.preventDefault();
                alert('Pilih minimal satu item untuk melakukan aksi.');
                return;
            }
            
            if (!action) {
                e.preventDefault();
                alert('Pilih aksi yang ingin dilakukan.');
                return;
            }
            
            const actionText = {
                'publish': 'mempublikasi',
                'unpublish': 'meng-unpublish',
                'feature': 'mem-feature',
                'unfeature': 'meng-unfeature',
                'delete': 'menghapus'
            };
            
            if (!confirm(`Yakin ingin ${actionText[action]} ${selected} item yang dipilih?`)) {
                e.preventDefault();
            }
        });

        // Auto-refresh data every 30 seconds for real-time updates
        setInterval(function() {
            // Only refresh if no checkboxes are selected to avoid disrupting user actions
            const selected = document.querySelectorAll('.item-checkbox:checked').length;
            if (selected === 0) {
                // You can implement auto-refresh logic here if needed
                console.log('Auto-refresh check...');
            }
        }, 30000);
    </script>
    @endpush
</x-admin-auction-layout>