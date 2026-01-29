@extends('layouts.admin')

@section('title', 'Kelola Brosur')

@section('content')
<x-admin.page-header title="Kelola Brosur" subtitle="Kelola brosur pembiayaan syariah">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.brochures.create') }}" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'>
            Upload Brosur
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card :noPadding="true">
    {{-- Desktop Table View --}}
    <div class="hidden md:block">
        <x-admin.table :headers="['Nama File', 'Ukuran', 'Diunggah Oleh', 'Tanggal Upload', 'Aksi']">
            @forelse($brochures as $brochure)
                <tr class="group hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-900 truncate">{{ $brochure->original_name }}</p>
                                <a href="{{ $brochure->download_url }}" target="_blank" class="text-sm text-blue-600 hover:underline">Download</a>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                        {{ number_format($brochure->file_size / 1024, 2) }} KB
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                        {{ $brochure->uploader ? $brochure->uploader->name : 'System' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                        {{ $brochure->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="confirmDelete('{{ $brochure->id }}', '{{ $brochure->original_name }}')" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="text-slate-500 font-medium">Belum ada brosur</p>
                        <p class="text-sm text-slate-400 mt-1">Klik tombol "Upload Brosur" untuk menambahkan</p>
                    </td>
                </tr>
            @endforelse
        </x-admin.table>
    </div>

    {{-- Pagination --}}
    @if($brochures->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $brochures->links() }}
        </div>
    @endif
</x-admin.card>

{{-- Delete Confirmation Modal --}}
<x-admin.delete-modal
    id="deleteModal"
    title="Hapus Brosur"
    message="Apakah Anda yakin ingin menghapus brosur ini? Tindakan ini tidak dapat dibatalkan."
/>

@push('scripts')
<script>
    function confirmDelete(id, name) {
        const modal = document.getElementById('deleteModal');
        const form = modal.querySelector('form');
        const messageEl = modal.querySelector('[data-message]');

        form.action = `{{ url('admin/brochures') }}/${id}`;
        if (messageEl) {
            messageEl.textContent = `Apakah Anda yakin ingin menghapus brosur "${name}"? Tindakan ini tidak dapat dibatalkan.`;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
</script>
@endpush
@endsection
