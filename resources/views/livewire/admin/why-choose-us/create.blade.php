<div>
    <x-admin.page-header title="Tambah Why Choose Us" subtitle="Tambahkan item Why Choose Us baru">
        <x-slot:actions>
            <x-admin.button href="{{ route('admin.why-choose-us.index') }}" variant="secondary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>'>
                Kembali
            </x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <x-admin.card title="Informasi Dasar" subtitle="Judul dan deskripsi item">
                    <div class="space-y-5">
                        <!-- Title -->
                        <div>
                            <x-admin.input 
                                label="Judul" 
                                name="title"
                                model="title"
                                required
                                placeholder="Contoh: Pelayanan Terbaik"
                                :error="$errors->first('title')" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-admin.textarea 
                                label="Deskripsi"
                                name="description"
                                model="description"
                                rows="4"
                                required
                                placeholder="Jelaskan mengapa ini menjadi keunggulan..."
                                :error="$errors->first('description')" />
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <x-admin.input 
                                label="Urutan Tampilan"
                                name="sort_order"
                                model="sort_order"
                                type="number"
                                min="0"
                                required
                                placeholder="0"
                                helper="Angka lebih kecil akan tampil lebih dulu"
                                :error="$errors->first('sort_order')" />
                        </div>
                    </div>
                </x-admin.card>

                {{-- Icon Upload --}}
                <x-admin.card title="Icon" subtitle="Upload icon untuk item">
                    <div class="space-y-5">
                        <!-- Icon File -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Icon File
                            </label>

                            <div x-data="{ hasFile: false }">
                                <input type="file"
                                       wire:model.lazy="icon"
                                       accept="image/png,image/svg+xml,image/jpeg,image/webp"
                                       class="hidden"
                                       id="icon"
                                       x-on:change="hasFile = $event.target.files.length > 0">
                                
                                <label for="icon" 
                                       class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 transition-all"
                                       :class="{ 'border-emerald-500 bg-emerald-50': hasFile }">
                                    <div wire:loading wire:target="icon" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center rounded-lg">
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
                                        <p class="text-xs text-gray-500 font-medium">Upload Icon</p>
                                        <p class="text-xs text-gray-400">PNG, SVG, JPEG, WEBP (Max 2MB)</p>
                                    </div>
                                </label>

                                @if($icon)
                                    <div class="mt-3 p-4 bg-emerald-50 rounded-lg border border-emerald-200">
                                        <p class="text-sm text-emerald-700 font-medium">
                                            File terpilih: {{ $icon->getClientOriginalName() }}
                                        </p>
                                        <button type="button" 
                                                wire:click="$set('icon', null)"
                                                class="mt-2 text-xs text-red-600 hover:text-red-700 font-medium">
                                            Batal Pilih
                                        </button>
                                    </div>
                                @endif

                                @error('icon')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <p class="text-xs text-gray-500 mt-2">
                                <strong>Rekomendasi:</strong> Icon SVG atau PNG transparan (64x64px)
                            </p>
                        </div>
                    </div>
                </x-admin.card>

                {{-- Theme Settings --}}
                <x-admin.card title="Tema Warna" subtitle="Pilih tema warna untuk item">
                    <div class="space-y-4">
                        <div>
                            <x-admin.select 
                                label="Tema Warna"
                                name="color_theme"
                                model="color_theme"
                                required
                                :options="$themes"
                                :error="$errors->first('color_theme')" />
                        </div>

                        <!-- Theme Preview -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 mb-3">Preview Tema:</p>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-{{ $color_theme ?? 'primary' }}-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-{{ $color_theme ?? 'primary' }}-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $title ?: 'Judul Contoh' }}</p>
                                    <p class="text-sm text-gray-500">{{ Str::limit($description ?: 'Deskripsi contoh item', 50) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-admin.card>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Status --}}
                <x-admin.card title="Status" subtitle="Atur status item">
                    <div class="pt-2">
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div>
                                <span class="text-sm font-semibold text-gray-700">Tampilkan Item</span>
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
                            <span wire:loading.remove wire:target="save">Simpan Item</span>
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
        document.addEventListener('DOMContentLoaded', () => {
            Livewire.on('showToast', (event) => {
                const { type, message } = event;
                
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
                
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                    toast.classList.add('translate-x-0');
                }, 100);
                
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (document.body.contains(toast)) {
                            document.body.removeChild(toast);
                        }
                    }, 300);
                }, 3000);
            });
        });
    </script>
</div>
