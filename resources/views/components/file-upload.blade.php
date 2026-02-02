@props([
    'name',
    'current' => null,
    'accept' => '*',
    'maxSize' => 2048,
    'help' => null,
    'multiple' => false
])

<div x-data="fileUpload('{{ $name }}', '{{ $current }}', {{ $maxSize }})" class="space-y-3">
    <!-- Current File Display -->
    @if($current)
        <div class="flex items-center space-x-3 p-3 bg-gray-100 rounded-lg">
            @if(str_contains($accept, 'image') && $current)
                <img :src="currentFileUrl" alt="Current file" class="w-16 h-16 object-cover rounded">
            @endif
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">File saat ini</p>
                <p class="text-sm text-gray-500" x-text="currentFileName"></p>
            </div>
            <button type="button" @click="removeCurrentFile()" 
                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                Hapus
            </button>
        </div>
    @endif

    <!-- File Input -->
    <div class="flex items-center justify-center w-full">
        <label for="{{ $name }}" 
               class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100"
               :class="{ 'border-red-500': hasError, 'border-green-500': hasFile }">
            
            <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!hasFile">
                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                </svg>
                <p class="mb-2 text-sm text-gray-500">
                    <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                </p>
                @if($help)
                    <p class="text-xs text-gray-500">{{ $help }}</p>
                @endif
            </div>

            <!-- Preview for new file -->
            <div x-show="hasFile" class="flex flex-col items-center justify-center pt-5 pb-6">
                <div x-show="isImage" class="mb-2">
                    <img :src="previewUrl" alt="Preview" class="w-16 h-16 object-cover rounded">
                </div>
                <p class="text-sm font-medium text-gray-900" x-text="fileName"></p>
                <p class="text-xs text-gray-500" x-text="fileSize"></p>
                <button type="button" @click="removeFile()" 
                        class="mt-2 text-red-600 hover:text-red-800 text-sm font-medium">
                    Hapus
                </button>
            </div>

            <input id="{{ $name }}" name="{{ $name }}" type="file" class="hidden" 
                   accept="{{ $accept }}" 
                   {{ $multiple ? 'multiple' : '' }}
                   @change="handleFileSelect($event)">
        </label>
    </div>

    <!-- Error Message -->
    <div x-show="errorMessage" class="text-red-600 text-sm" x-text="errorMessage"></div>

    <!-- Hidden input for deletion -->
    <input type="hidden" :name="'{{ $name }}_delete'" x-model="shouldDelete">
</div>