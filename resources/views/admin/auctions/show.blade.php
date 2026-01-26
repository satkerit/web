<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Lelang: {{ $auction->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.auctions.edit', $auction) }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('auctions.show', $auction) }}" target="_blank"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Lihat di Frontend
                </a>
                <a href="{{ route('admin.auctions.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Status and Basic Info -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        <div class="lg:col-span-2">
                            <div class="flex items-center justify-between mb-4">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $auction->title }}</h1>
                                <div class="flex space-x-2">
                                    @if($auction->is_featured)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            Featured
                                        </span>
                                    @endif
                                    @if($auction->is_urgent)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            Urgent
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $auction->status_color['bg'] }} {{ $auction->status_color['text'] }}">
                                        {{ $auction->status_label }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($auction->description)
                                <p class="text-gray-700 mb-4">{{ $auction->description }}</p>
                            @endif

                            <!-- Images -->
                            @if($auction->images && count($auction->images) > 0)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold mb-3">Foto Objek Lelang</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($auction->images as $image)
                                            <img src="{{ \App\Helpers\StorageHelper::url($image) }}" 
                                                 alt="Foto {{ $auction->title }}"
                                                 class="w-full h-32 object-cover rounded-lg shadow-md">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Quick Stats -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Informasi Cepat</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-gray-600">Nomor Lelang:</span>
                                    <div class="font-medium">{{ $auction->auction_number }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Jenis Aset:</span>
                                    <div class="font-medium">{{ $auction->asset_type_label }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Harga Limit:</span>
                                    <div class="font-medium text-green-600">{{ $auction->formatted_limit_price }}</div>
                                </div>
                                @if($auction->estimated_price)
                                    <div>
                                        <span class="text-sm text-gray-600">Nilai Taksiran:</span>
                                        <div class="font-medium">{{ $auction->formatted_estimated_price }}</div>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm text-gray-600">Tanggal Lelang:</span>
                                    <div class="font-medium">
                                        @if($auction->auction_date)
                                            {{ $auction->auction_date->format('d F Y, H:i') }} WIB
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Lokasi Lelang:</span>
                                    <div class="font-medium">{{ $auction->auction_location }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">Statistik:</span>
                                    <div class="text-sm">
                                        <div>Views: {{ number_format($auction->view_count) }}</div>
                                        <div>Interest: {{ number_format($auction->interest_count) }}</div>
                                        <div>Downloads: {{ number_format($auction->download_count) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Information Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <button class="tab-button active border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" 
                                    data-tab="property">
                                Properti
                            </button>
                            <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" 
                                    data-tab="auction">
                                Lelang
                            </button>
                            <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" 
                                    data-tab="legal">
                                Legal
                            </button>
                            <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm" 
                                    data-tab="contact">
                                Kontak
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Contents -->
                    <div class="tab-content" id="property">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg mb-3">Detail Properti</h4>
                                <div class="space-y-2">
                                    @if($auction->asset_category)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kategori:</span>
                                            <span class="font-medium">{{ $auction->asset_category }}</span>
                                        </div>
                                    @endif
                                    @if($auction->land_area)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Luas Tanah:</span>
                                            <span class="font-medium">{{ number_format($auction->land_area, 0) }} m²</span>
                                        </div>
                                    @endif
                                    @if($auction->building_area)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Luas Bangunan:</span>
                                            <span class="font-medium">{{ number_format($auction->building_area, 0) }} m²</span>
                                        </div>
                                    @endif
                                    @if($auction->floors)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Jumlah Lantai:</span>
                                            <span class="font-medium">{{ $auction->floors }}</span>
                                        </div>
                                    @endif
                                    @if($auction->bedrooms)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kamar Tidur:</span>
                                            <span class="font-medium">{{ $auction->bedrooms }}</span>
                                        </div>
                                    @endif
                                    @if($auction->bathrooms)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kamar Mandi:</span>
                                            <span class="font-medium">{{ $auction->bathrooms }}</span>
                                        </div>
                                    @endif
                                    @if($auction->parking_spaces)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Tempat Parkir:</span>
                                            <span class="font-medium">{{ $auction->parking_spaces }}</span>
                                        </div>
                                    @endif
                                    @if($auction->year_built)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Tahun Dibangun:</span>
                                            <span class="font-medium">{{ $auction->year_built }}</span>
                                        </div>
                                    @endif
                                    @if($auction->building_condition)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kondisi Bangunan:</span>
                                            <span class="font-medium">{{ $auction->building_condition }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-3">Lokasi</h4>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-gray-600">Alamat:</span>
                                        <div class="font-medium">{{ $auction->full_address }}</div>
                                    </div>
                                    @if($auction->facilities)
                                        <div>
                                            <span class="text-gray-600">Fasilitas:</span>
                                            <div class="font-medium">{{ $auction->facilities }}</div>
                                        </div>
                                    @endif
                                    @if($auction->nearby_facilities)
                                        <div>
                                            <span class="text-gray-600">Fasilitas Sekitar:</span>
                                            <div class="font-medium">{{ $auction->nearby_facilities }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content hidden" id="auction">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg mb-3">Informasi Lelang</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Jenis Lelang:</span>
                                        <span class="font-medium">{{ $auction->auction_type_label }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Metode:</span>
                                        <span class="font-medium">{{ $auction->auction_method ?? 'Lelang Terbuka' }}</span>
                                    </div>
                                    @if($auction->registration_start && $auction->registration_end)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Pendaftaran:</span>
                                            <span class="font-medium">{{ $auction->registration_start->format('d/m/Y') }} - {{ $auction->registration_end->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                    @if($auction->viewing_start && $auction->viewing_end)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Viewing:</span>
                                            <span class="font-medium">{{ $auction->viewing_start->format('d/m/Y') }} - {{ $auction->viewing_end->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-3">Harga & Pembayaran</h4>
                                <div class="space-y-2">
                                    @if($auction->calculated_deposit)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Uang Jaminan:</span>
                                            <span class="font-medium">{{ $auction->formatted_calculated_deposit }}</span>
                                        </div>
                                    @endif
                                    @if($auction->increment_amount)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kelipatan Penawaran:</span>
                                            <span class="font-medium">Rp {{ number_format($auction->increment_amount, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Batas Pelunasan:</span>
                                        <span class="font-medium">{{ $auction->payment_deadline_days }} hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content hidden" id="legal">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg mb-3">Sertifikat</h4>
                                <div class="space-y-2">
                                    @if($auction->certificate_type)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Jenis Sertifikat:</span>
                                            <span class="font-medium">{{ $auction->certificate_type_label }}</span>
                                        </div>
                                    @endif
                                    @if($auction->certificate_number)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Nomor Sertifikat:</span>
                                            <span class="font-medium">{{ $auction->certificate_number }}</span>
                                        </div>
                                    @endif
                                    @if($auction->certificate_date)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Tanggal Terbit:</span>
                                            <span class="font-medium">{{ $auction->certificate_date->format('d F Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-3">Legal</h4>
                                <div class="space-y-2">
                                    @if($auction->creditor_name)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kreditur:</span>
                                            <span class="font-medium">{{ $auction->creditor_name }}</span>
                                        </div>
                                    @endif
                                    @if($auction->debt_amount)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Jumlah Hutang:</span>
                                            <span class="font-medium">Rp {{ number_format($auction->debt_amount, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    @if($auction->court_decision)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Putusan Pengadilan:</span>
                                            <span class="font-medium">{{ $auction->court_decision }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content hidden" id="contact">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-lg mb-3">Penyelenggara</h4>
                                <div class="space-y-2">
                                    @if($auction->organizer_name)
                                        <div>
                                            <span class="text-gray-600">Nama:</span>
                                            <div class="font-medium">{{ $auction->organizer_name }}</div>
                                        </div>
                                    @endif
                                    @if($auction->organizer_address)
                                        <div>
                                            <span class="text-gray-600">Alamat:</span>
                                            <div class="font-medium">{{ $auction->organizer_address }}</div>
                                        </div>
                                    @endif
                                    @if($auction->organizer_phone)
                                        <div>
                                            <span class="text-gray-600">Telepon:</span>
                                            <div class="font-medium">{{ $auction->organizer_phone }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-3">Kontak Person</h4>
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-gray-600">Nama:</span>
                                        <div class="font-medium">{{ $auction->contact_person }}</div>
                                    </div>
                                    @if($auction->contact_position)
                                        <div>
                                            <span class="text-gray-600">Jabatan:</span>
                                            <div class="font-medium">{{ $auction->contact_position }}</div>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-gray-600">Telepon:</span>
                                        <div class="font-medium">{{ $auction->contact_phone }}</div>
                                    </div>
                                    @if($auction->contact_email)
                                        <div>
                                            <span class="text-gray-600">Email:</span>
                                            <div class="font-medium">{{ $auction->contact_email }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Tab functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.dataset.tab;
                
                // Remove active class from all buttons
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                
                // Add active class to clicked button
                this.classList.add('active', 'border-indigo-500', 'text-indigo-600');
                this.classList.remove('border-transparent', 'text-gray-500');
                
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Show selected tab content
                document.getElementById(tabId).classList.remove('hidden');
            });
        });

        // Set first tab as active
        document.querySelector('.tab-button[data-tab="property"]').classList.add('border-indigo-500', 'text-indigo-600');
        document.querySelector('.tab-button[data-tab="property"]').classList.remove('border-transparent', 'text-gray-500');
    </script>
    @endpush
</x-app-layout>