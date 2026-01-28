<div>
    <x-admin.page-header 
        title="Pengaturan Section Why Choose Us" 
        subtitle="Kelola gambar dan teks untuk section Why Choose Us di halaman utama">
        <x-slot:actions>
            <x-admin.button href="{{ route('admin.why-choose-us.index') }}" variant="secondary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>'>
                Kelola Items
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <form wire:submit="save" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Text Content --}}
                <x-admin.card title="Konten Teks" subtitle="Judul dan subtitle section">
                    <div class="space-y-5">
                        <!-- Section Title -->
                        <div>
                            <x-admin.input 
                                label="Judul Section" 
                                name="section_title"
                                model="section_title"
                                required
                                placeholder="Contoh: Mengapa Memilih Kami"
                                :error="$errors->first('section_title')" />
                        </div>

                        <!-- Section Subtitle -->
                        <div>
                            <x-admin.textarea 
                                label="Subtitle Section"
                                name="section_subtitle"
                                model="section_subtitle"
                                rows="3"
                                placeholder="Deskripsi singkat tentang keunggulan Anda..."
                                :error="$errors->first('section_subtitle')" />
                        </div>

                        <!-- Badge Text -->
                        <div>
                            <x-admin.input 
                                label="Teks Badge"
                                name="badge_text"
                                model="badge_text"
                                placeholder="Contoh: 100% Syariah Compliant"
                                helper="Badge yang muncul di pojok gambar section"
                                :error="$errors->first('badge_text')" />
                        </div>
                    </div>
                </x-admin.card>

                {{-- Images --}}
                <x-admin.card title="Gambar Section" subtitle="Upload gambar utama dan icon badge">
                    <div class="space-y-6">
                        <!-- Section Image -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Gambar Section Utama
                            </label>
                            
                            @if($current_section_image)
                                <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="relative">
                                        <img src="{{ $this->getImageUrl($current_section_image) }}" 
                                             alt="Current section image" 
                                             class="w-full h-48 object-cover rounded">
                                        <button type="button"
                                                wire:click="removeSectionImage"
                                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 text-center mt-2">Gambar section saat ini</p>
                                </div>
                            @endif

                            <div x-data="{ hasFile: false }">
                                <input type="file"
                                       wire:model.lazy="section_image"
                                       accept="image/png,image/jpeg,image/jpg,image/webp"
                                       class="hidden"
                                       id="section_image"
                                       x-on:change="hasFile = $event.target.files.length > 0">
                                
                                <label for="section_image" 
                                       class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 transition-all"
                                       :class="{ 'border-emerald-500 bg-emerald-50': hasFile }">
                                    <div wire:loading wire:target="section_image" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center rounded-lg">
                                        <div class="text-emerald-600">
                                            <svg class="animate-spin h-8 w-8" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm text-gray-500 font-medium mb-1">Upload Gambar Section</p>
                                        <p class="text-xs text-gray-400">PNG, JPG, WEBP (Max 5MB)</p>
                                    </div>
                                </label>

                                @if($section_image)
                                    <div class="mt-3 p-4 bg-emerald-50 rounded-lg border border-emerald-200">
                                        <p class="text-sm text-emerald-700 font-medium">
                                            File terpilih: {{ $section_image->getClientOriginalName() }}
                                        </p>
                                        <button type="button" 
                                                wire:click="$set('section_image', null)"
                                                class="mt-2 text-xs text-red-600 hover:text-red-700 font-medium">
                                            Batal Pilih
                                        </button>
                                    </div>
                                @endif

                                @error('section_image')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <p class="text-xs text-gray-500 mt-2">
                                <strong>Rekomendasi:</strong> Gambar landscape (1200x800px) untuk tampilan optimal
                            </p>
                        </div>

                        <div class="border-t border-gray-200 pt-6"></div>

                        <!-- Badge Icon -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Icon Badge
                            </label>
                            
                            @if($current_badge_icon)
                                <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="relative">
                                        <img src="{{ $this->getImageUrl($current_badge_icon) }}" 
                                             alt="Current badge icon" 
                                             class="w-16 h-16 object-contain mx-auto">
                                        <button type="button"
                                                wire:click="removeBadgeIcon"
                                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full shadow-lg transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 text-center mt-2">Icon badge saat ini</p>
                                </div>
                            @endif

                            <div x-data="{ hasFile: false }">
                                <input type="file"
                                       wire:model.lazy="badge_icon"
                                       accept="image/png,image/svg+xml,image/jpeg,image/webp"
                                       class="hidden"
                                       id="badge_icon"
                                       x-on:change="hasFile = $event.target.files.length > 0">
                                
                                <label for="badge_icon" 
                                       class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 transition-all"
                                       :class="{ 'border-emerald-500 bg-emerald-50': hasFile }">
                                    <div wire:loading wire:target="badge_icon" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center rounded-lg">
                                        <div class="text-emerald-600">
                                            <svg class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-xs text-gray-500 font-medium">Upload Icon Badge</p>
                                        <p class="text-xs text-gray-400">PNG, SVG (Max 2MB)</p>
                                    </div>
                                </label>

                                @if($badge_icon)
                                    <div class="mt-3 p-4 bg-emerald-50 rounded-lg border border-emerald-200">
                                        <p class="text-sm text-emerald-700 font-medium">
                                            File terpilih: {{ $badge_icon->getClientOriginalName() }}
                                        </p>
                                        <button type="button" 
                                                wire:click="$set('badge_icon', null)"
                                                class="mt-2 text-xs text-red-600 hover:text-red-700 font-medium">
                                            Batal Pilih
                                        </button>
                                    </div>
                                @endif

                                @error('badge_icon')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <p class="text-xs text-gray-500 mt-2">
                                <strong>Rekomendasi:</strong> Icon SVG atau PNG transparan (48x48px)
                            </p>
                        </div>
                    </div>
                </x-admin.card>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Preview --}}
                <x-admin.card title="Preview" subtitle="Tampilan section">
                    <div class="space-y-4">
                        <div class="relative rounded-lg overflow-hidden border border-gray-200">
                            @if($current_section_image)
                                <img src="{{ $this->getImageUrl($current_section_image) }}" 
                                     alt="Preview" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            @if($badge_text)
                                <div class="absolute bottom-4 right-4 bg-white rounded-lg shadow-lg p-3 flex items-center gap-2">
                                    @if($current_badge_icon)
                                        <img src="{{ $this->getImageUrl($current_badge_icon) }}" 
                                             alt="Badge" 
                                             class="w-6 h-6">
                                    @else
                                        <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                    <span class="text-sm font-semibold text-gray-900">{{ $badge_text }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-center">
                            <h3 class="text-lg font-bold text-gray-900">{{ $section_title ?: 'Judul Section' }}</h3>
                            @if($section_subtitle)
                                <p class="text-sm text-gray-600 mt-1">{{ $section_subtitle }}</p>
                            @endif
                        </div>
                    </div>
                </x-admin.card>

                {{-- Settings --}}
                <x-admin.card title="Status" subtitle="Aktifkan/nonaktifkan section">
                    <div class="pt-2">
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div>
                                <span class="text-sm font-semibold text-gray-700">Tampilkan Section</span>
                                <p class="text-xs text-gray-500 mt-0.5">Aktifkan untuk menampilkan di frontend</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox"
                                       wire:model="is_active"
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </div>
                        </label>
                    </div>
                </x-admin.card>

                {{-- Action Buttons --}}
                <x-admin.card :noPadding="true">
                    <div class="p-5 space-y-3">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-400 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg wire:loading wire:target="save" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="save">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                            <span wire:loading.remove wire:target="save">Simpan Pengaturan</span>
                        </button>
                        
                        <a href="{{ route('admin.why-choose-us.index') }}" 
                           class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </x-admin.card>
            </div>
        </div>
    </form>

    {{-- Toast Notification --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('showToast', (event) => {
                const { type, message } = event;
                
                // Create toast element
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${
                    type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                }`;
                toast.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            ${type === 'success' 
                                ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
                                : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
                            }
                        </svg>
                        <span class="font-medium">${message}</span>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                    toast.classList.add('translate-x-0');
                }, 100);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 300);
                }, 3000);
            });
        });
    </script>
</div>
