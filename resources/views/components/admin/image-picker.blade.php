@props([
    'name',
    'label' => null,
    'value' => null,
    'accept' => 'image/*',
    'hint' => null,
    'required' => false,
    'previewClass' => 'h-20',
])

@php
    use App\Helpers\StorageHelper;
    $inputId = 'input_' . $name;
    $deleteFieldName = $name . '_delete';
    $previewUrl = '';
    $hasExistingImage = !empty($value);
    if ($value) {
        $previewUrl = StorageHelper::url($value);
    }
@endphp

<div x-data="imagePicker({
    inputId: '{{ $inputId }}',
    initialPreview: '{{ $previewUrl }}',
    hasExistingImage: {{ $hasExistingImage ? 'true' : 'false' }},
    deleteFieldName: '{{ $deleteFieldName }}',
})" class="group">
    @if($label)
        <label class="block text-sm font-semibold text-slate-700 mb-1.5 ml-0.5">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    {{-- Preview --}}
    <div class="mb-3" x-show="previewUrl" x-cloak>
        <img :src="previewUrl" alt="Preview" class="{{ $previewClass }} rounded-lg object-contain bg-gray-100 border">
    </div>

    {{-- Hidden input for storage path --}}
    <input type="hidden" name="{{ $name }}_from_storage" :value="fromStorage ? storagePath : ''">

    {{-- Hidden input for delete flag --}}
    <input type="hidden" name="{{ $name }}_delete" :value="shouldDelete ? '1' : ''">

    {{-- Buttons --}}
    <div class="flex flex-wrap gap-2">
        {{-- Upload from PC --}}
        <label class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-100 cursor-pointer transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Upload dari PC
            <input type="file" name="{{ $name }}" id="{{ $inputId }}" accept="{{ $accept }}" class="hidden" @change="handleFileSelect($event)">
        </label>

        {{-- Select from Storage --}}
        <button type="button" @click="openStorageModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            Pilih dari Storage
        </button>

        {{-- Clear --}}
        <button type="button" x-show="previewUrl" @click="clearSelection()" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Hapus
        </button>
    </div>

    @if($hint)
        <p class="mt-1.5 text-xs text-slate-500 ml-0.5">{{ $hint }}</p>
    @endif

    {{-- Storage Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/50" @click="closeStorageModal()"></div>
            <div class="relative bg-white rounded-xl max-w-4xl w-full max-h-[80vh] overflow-hidden shadow-xl">
                {{-- Header --}}
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Pilih Gambar dari Storage</h3>
                    <button type="button" @click="closeStorageModal()" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 p-4 bg-gray-50 border-b text-sm">
                    <button type="button" @click="navigateTo('')" class="text-blue-600 hover:text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </button>
                    <template x-for="(crumb, index) in breadcrumbs" :key="index">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400">/</span>
                            <button type="button" @click="navigateTo(crumb.path)" class="text-blue-600 hover:text-blue-700" x-text="crumb.name"></button>
                        </div>
                    </template>
                </div>

                {{-- Content --}}
                <div class="p-4 overflow-y-auto" style="max-height: 400px;">
                    <div x-show="loading" class="text-center py-8 text-gray-500">Memuat...</div>
                    <div x-show="!loading && items.length === 0" class="text-center py-8 text-gray-500">Folder kosong</div>
                    <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        <template x-for="item in items" :key="item.path">
                            <div @click="item.type === 'folder' ? navigateTo(item.path) : selectImage(item)"
                                 :class="{'ring-2 ring-blue-500': selectedItem?.path === item.path}"
                                 class="relative group cursor-pointer rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all overflow-hidden">
                                <template x-if="item.type === 'folder'">
                                    <div class="aspect-square flex flex-col items-center justify-center bg-gray-50 p-2">
                                        <svg class="w-12 h-12 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                                        </svg>
                                        <span class="text-xs text-gray-600 mt-1 truncate w-full text-center" x-text="item.name"></span>
                                    </div>
                                </template>
                                <template x-if="item.type === 'file' && item.isImage">
                                    <div class="aspect-square">
                                        <img :src="item.url" :alt="item.name" class="w-full h-full object-cover">
                                        <div class="absolute inset-x-0 bottom-0 bg-black/60 p-1">
                                            <span class="text-xs text-white truncate block" x-text="item.name"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 p-4 border-t bg-gray-50">
                    <button type="button" @click="closeStorageModal()" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 rounded-lg">Batal</button>
                    <button type="button" @click="confirmSelection()" :disabled="!selectedItem" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed">Pilih</button>
                </div>
            </div>
        </div>
    </div>
</div>
