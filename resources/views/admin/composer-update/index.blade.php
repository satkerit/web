@extends('layouts.admin')

@section('title', 'Composer Update')

@section('content')
<div class="space-y-6">
    <x-admin.page-header title="Composer Update" subtitle="Perbarui dependensi Laravel dan paket lainnya">
    </x-admin.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Environment Info --}}
        <x-admin.card>
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Informasi Lingkungan</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="text-sm text-slate-600">Laravel Version</span>
                    <span class="font-mono text-sm font-semibold text-blue-600">{{ $laravelVersion }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="text-sm text-slate-600">PHP Version</span>
                    <span class="font-mono text-sm font-semibold text-green-600">{{ $phpVersion }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="text-sm text-slate-600">Composer Version</span>
                    <span class="font-mono text-sm font-semibold text-purple-600">{{ $composerVersion }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="text-sm text-slate-600">Environment</span>
                    <span class="text-sm font-semibold {{ app()->environment('production') ? 'text-red-600' : 'text-yellow-600' }}">{{ ucfirst(app()->environment()) }}</span>
                </div>
            </div>
        </x-admin.card>

        {{-- Run Update --}}
        <x-admin.card>
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Jalankan Composer Update</h3>
            <p class="text-sm text-slate-500 mb-4">Pastikan untuk membuat cadangan database dan file sebelum melakukan update!</p>

            <form id="composerUpdateForm" class="space-y-4">
                @csrf

                {{-- Confirmation --}}
                <div class="flex items-start gap-2 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                    <input type="checkbox"
                           id="confirm"
                           name="confirm"
                           class="mt-1 rounded border-amber-300 text-blue-600 focus:ring-blue-500"
                           required>
                    <label for="confirm" class="text-sm text-amber-800">
                        Saya yakin telah membuat cadangan database dan file penting, serta siap untuk melanjutkan update.
                    </label>
                </div>

                {{-- Submit Button --}}
                <div class="flex items-center justify-end gap-3">
                    <button type="submit"
                            id="updateBtn"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        Jalankan Composer Update
                    </button>
                </div>
            </form>
        </x-admin.card>
    </div>

    {{-- Output --}}
    <x-admin.card id="outputCard" class="hidden">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900">Output</h3>
            <button id="copyBtn" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Salin Output
            </button>
        </div>
        <div id="outputContainer" class="bg-slate-900 text-slate-100 rounded-xl p-4 font-mono text-xs overflow-x-auto max-h-[400px] overflow-y-auto">
            <pre id="outputContent"></pre>
        </div>
    </x-admin.card>
</div>

@push('scripts')
<script nonce="{{ $nonce }}">
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('composerUpdateForm');
    const updateBtn = document.getElementById('updateBtn');
    const outputCard = document.getElementById('outputCard');
    const outputContent = document.getElementById('outputContent');
    const copyBtn = document.getElementById('copyBtn');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Disable button and show loading state
        updateBtn.disabled = true;
        updateBtn.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;

        try {
            const formData = new FormData(form);
            const response = await fetch('{{ route('admin.composer-update.run') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            outputCard.classList.remove('hidden');
            outputContent.textContent = data.output || data.message;

            if (data.success) {
                outputContainer.classList.remove('border-red-500', 'bg-red-900/20');
                outputContainer.classList.add('border-green-500', 'bg-green-900/20');
            } else {
                outputContainer.classList.remove('border-green-500', 'bg-green-900/20');
                outputContainer.classList.add('border-red-500', 'bg-red-900/20');
            }

            // Scroll to bottom
            outputContainer.scrollTop = outputContainer.scrollHeight;

        } catch (error) {
            outputCard.classList.remove('hidden');
            outputContent.textContent = 'Error: ' + error.message;
            outputContainer.classList.remove('border-green-500', 'bg-green-900/20');
            outputContainer.classList.add('border-red-500', 'bg-red-900/20');
        } finally {
            updateBtn.disabled = false;
            updateBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                Jalankan Composer Update
            `;
        }
    });

    copyBtn.addEventListener('click', function() {
        navigator.clipboard.writeText(outputContent.textContent).then(() => {
            copyBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Disalin!
            `;
            setTimeout(() => {
                copyBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Salin Output
                `;
            }, 2000);
        });
    });
});
</script>
@endpush
@endsection
