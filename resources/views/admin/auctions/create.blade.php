<x-admin-auction-layout>
    <x-slot name="header">Tambah Lelang Agunan</x-slot>
    <x-slot name="subtitle">Buat lelang agunan baru dengan informasi lengkap</x-slot>

    <!-- Header Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Tambah Lelang Agunan Baru</h2>
            <p class="text-gray-600 mt-2">Lengkapi semua informasi yang diperlukan untuk lelang agunan</p>
        </div>
        <a href="{{ route('admin.auctions.index') }}" 
           class="btn-auction-admin-secondary inline-flex items-center space-x-2 px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="admin-auction-card animate-fade-in-up">
        <div class="p-8">
            <form method="POST" action="{{ route('admin.auctions.store') }}" enctype="multipart/form-data" id="auction-form">
                @csrf
                
                <!-- Basic Information Section -->
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Lelang Agunan *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="admin-auction-input w-full" required
                                   placeholder="Contoh: Rumah Mewah 2 Lantai di Pangkalpinang">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="auction_number" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Lelang Agunan *</label>
                            <input type="text" name="auction_number" id="auction_number" value="{{ old('auction_number') }}" 
                                   class="admin-auction-input w-full" required
                                   placeholder="Contoh: LA-2026-001">
                            @error('auction_number')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="asset_type" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Aset *</label>
                            <select name="asset_type" id="asset_type" class="admin-auction-input w-full" required>
                                <option value="">Pilih Jenis Aset</option>
                                @foreach(\App\Models\Auction::$assetTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('asset_type') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_type')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">Kota</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" 
                                   class="admin-auction-input w-full"
                                   placeholder="Contoh: Pangkalpinang">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="limit_price" class="block text-sm font-semibold text-gray-700 mb-2">Harga Limit *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="limit_price" id="limit_price" value="{{ old('limit_price') }}" 
                                       class="admin-auction-input w-full pl-12" required
                                       placeholder="850000000">
                            </div>
                            @error('limit_price')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="auction_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal & Waktu Lelang *</label>
                            <input type="datetime-local" name="auction_date" id="auction_date" value="{{ old('auction_date') }}" 
                                   class="admin-auction-input w-full" required>
                            @error('auction_date')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="auction_type" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Lelang *</label>
                            <select name="auction_type" id="auction_type" class="admin-auction-input w-full" required>
                                <option value="">Pilih Jenis Lelang</option>
                                @foreach(\App\Models\Auction::$auctionTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('auction_type') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('auction_type')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="auction_location" class="block text-sm font-semibold text-gray-700 mb-2">Lokasi Lelang *</label>
                            <input type="text" name="auction_location" id="auction_location" value="{{ old('auction_location') }}" 
                                   class="admin-auction-input w-full" required
                                   placeholder="Contoh: Kantor BPRS Bangka Belitung">
                            @error('auction_location')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_person" class="block text-sm font-semibold text-gray-700 mb-2">Kontak Person *</label>
                            <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" 
                                   class="admin-auction-input w-full" required
                                   placeholder="Nama lengkap kontak person">
                            @error('contact_person')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-semibold text-gray-700 mb-2">Telepon Kontak *</label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" 
                                   class="admin-auction-input w-full" required
                                   placeholder="Contoh: 0717-123456">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" class="admin-auction-input w-full" required>
                                @foreach(\App\Models\Auction::$statusLabels as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', 'draft') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        Alamat Objek
                    </h3>
                    
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap *</label>
                        <textarea name="address" id="address" rows="4" 
                                  class="admin-auction-input w-full" required
                                  placeholder="Masukkan alamat lengkap objek lelang...">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Description Section -->
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                        </div>
                        Deskripsi
                    </h3>
                    
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Objek</label>
                        <textarea name="description" id="description" rows="6" 
                                  class="admin-auction-input w-full"
                                  placeholder="Deskripsikan objek lelang secara detail...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Images Section -->
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        Gambar Objek
                    </h3>
                    
                    <div>
                        <label for="images" class="block text-sm font-semibold text-gray-700 mb-2">Upload Gambar</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-orange-400 transition-colors">
                            <input type="file" name="images[]" id="images" multiple accept="image/*" 
                                   class="hidden" onchange="previewImages(this)">
                            <label for="images" class="cursor-pointer">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-gray-600 font-medium">Klik untuk upload gambar</p>
                                <p class="text-gray-400 text-sm mt-1">PNG, JPG, JPEG hingga 5MB per file</p>
                            </label>
                        </div>
                        <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a href="{{ route('admin.auctions.index') }}" 
                       class="btn-auction-admin-secondary px-8 py-3 rounded-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" 
                            class="btn-auction-admin-primary px-8 py-3 rounded-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Lelang
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image preview functionality
        function previewImages(input) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                preview.classList.remove('hidden');
                
                Array.from(input.files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative group';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg shadow-md">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <span class="text-white text-xs font-medium">${file.name}</span>
                                </div>
                            `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                preview.classList.add('hidden');
            }
        }

        // Form validation
        document.getElementById('auction-form').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500', 'bg-red-50');
                    isValid = false;
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else {
                    field.classList.remove('border-red-500', 'bg-red-50');
                }
            });

            if (!isValid) {
                e.preventDefault();
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50';
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Mohon lengkapi semua field yang wajib diisi.
                    </div>
                `;
                document.body.appendChild(errorDiv);
                
                setTimeout(() => {
                    errorDiv.remove();
                }, 5000);
            }
        });

        // Auto-format price input
        document.getElementById('limit_price').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                // Add thousand separators for display (optional)
                const formatted = new Intl.NumberFormat('id-ID').format(value);
                // You can show formatted value in a separate display element if needed
            }
        });

        // Auto-generate auction number based on current date
        document.addEventListener('DOMContentLoaded', function() {
            const auctionNumberField = document.getElementById('auction_number');
            if (!auctionNumberField.value) {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                auctionNumberField.value = `LA-${year}${month}${day}-${random}`;
            }
        });

        // Enhanced form interactions
        document.querySelectorAll('.admin-auction-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.classList.add('ring-2', 'ring-orange-500', 'border-orange-500');
            });
            
            input.addEventListener('blur', function() {
                this.classList.remove('ring-2', 'ring-orange-500', 'border-orange-500');
            });
        });
    </script>
    @endpush
</x-admin-auction-layout>