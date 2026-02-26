@extends('layouts.admin')

@section('title', 'Upload Brosur')

@section('content')
<x-admin.page-header title="Upload Brosur" subtitle="Upload file brosur baru (PDF)">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.brochures.index') }}" variant="secondary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>'>
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card>
    <div x-data="fileUpload()" class="max-w-xl mx-auto py-8">
        <div 
            class="relative border-2 border-dashed rounded-xl p-10 text-center transition-all duration-200"
            :class="{ 'border-blue-500 bg-green-50': isDropping, 'border-slate-300 hover:border-blue-400': !isDropping }"
            @dragover.prevent="isDropping = true"
            @dragleave.prevent="isDropping = false"
            @drop.prevent="handleDrop($event)"
        >
            <input type="file" x-ref="fileInput" class="hidden" accept=".pdf" @change="handleFileSelect($event)">
            
            <div x-show="!file && !isUploading">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-1">Upload File Brosur</h3>
                <p class="text-slate-500 mb-4 text-sm">Drag & drop file PDF di sini atau klik untuk memilih</p>
                <button type="button" @click="$refs.fileInput.click()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Pilih File
                </button>
                <p class="mt-4 text-xs text-slate-400">Hanya format PDF. Maksimal 10MB.</p>
            </div>

            <div x-show="file" style="display: none;">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-slate-900" x-text="file?.name"></p>
                        <p class="text-sm text-slate-500" x-text="formatSize(file?.size)"></p>
                    </div>
                </div>

                <div x-show="isUploading" class="w-full bg-slate-200 rounded-full h-2.5 mb-4">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
                </div>

                <div class="flex justify-center gap-3">
                    <button 
                        type="button" 
                        @click="file = null; progress = 0; isUploading = false"
                        class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50"
                        :disabled="isUploading"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        @click="uploadFile()"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                        :disabled="isUploading"
                    >
                        <span x-show="!isUploading">Upload Sekarang</span>
                        <span x-show="isUploading" x-text="`Uploading ${progress}%`"></span>
                    </button>
                </div>
            </div>
            
            <div x-show="errorMessage" x-text="errorMessage" class="mt-4 text-sm text-red-600 font-medium" style="display: none;"></div>
        </div>
    </div>
</x-admin.card>

@push('scripts')
<script>
    function fileUpload() {
        return {
            isDropping: false,
            file: null,
            progress: 0,
            isUploading: false,
            errorMessage: '',

            handleDrop(e) {
                this.isDropping = false;
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    this.validateAndSetFile(files[0]);
                }
            },

            handleFileSelect(e) {
                const files = e.target.files;
                if (files.length > 0) {
                    this.validateAndSetFile(files[0]);
                }
            },

            validateAndSetFile(file) {
                this.errorMessage = '';
                if (file.type !== 'application/pdf') {
                    this.errorMessage = 'File harus berformat PDF.';
                    return;
                }
                if (file.size > 10 * 1024 * 1024) { // 10MB
                    this.errorMessage = 'Ukuran file maksimal 10MB.';
                    return;
                }
                this.file = file;
            },

            formatSize(bytes) {
                if (!bytes) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            uploadFile() {
                if (!this.file) return;

                this.isUploading = true;
                this.progress = 0;
                this.errorMessage = '';

                let formData = new FormData();
                formData.append('file', this.file);

                axios.post('{{ route('admin.brochures.store') }}', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: (progressEvent) => {
                        this.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                })
                .then(response => {
                    window.location.href = '{{ route('admin.brochures.index') }}';
                })
                .catch(error => {
                    this.isUploading = false;
                    this.errorMessage = error.response?.data?.message || 'Terjadi kesalahan saat upload.';
                    console.error(error);
                });
            }
        }
    }
</script>
@endpush
@endsection
