@extends('layouts.admin')

@section('title', 'File Manager')

@section('content')
<x-admin.page-header title="File Manager" subtitle="Kelola file dan folder di storage">
    <x-slot:actions>
        <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Upload File
        </button>
        <button onclick="document.getElementById('folderModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            Folder Baru
        </button>
    </x-slot:actions>
</x-admin.page-header>

{{-- Storage Info --}}
<div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl p-4 border border-gray-200">
        <p class="text-sm text-gray-500">Total Storage</p>
        <p class="text-xl font-semibold text-gray-900">{{ number_format($storageInfo['total'] / 1024 / 1024 / 1024, 2) }} GB</p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-gray-200">
        <p class="text-sm text-gray-500">Terpakai</p>
        <p class="text-xl font-semibold text-emerald-600">{{ number_format($storageInfo['used'] / 1024 / 1024 / 1024, 2) }} GB</p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-gray-200">
        <p class="text-sm text-gray-500">Tersedia</p>
        <p class="text-xl font-semibold text-blue-600">{{ number_format($storageInfo['free'] / 1024 / 1024 / 1024, 2) }} GB</p>
    </div>
</div>

{{-- Breadcrumbs --}}
<div class="mb-4 flex items-center gap-2 text-sm">
    <a href="{{ route('admin.storage.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
    </a>
    @foreach($breadcrumbs as $crumb)
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.storage.index', ['path' => $crumb['path']]) }}" class="text-emerald-600 hover:text-emerald-700">
            {{ $crumb['name'] }}
        </a>
    @endforeach
</div>

<x-admin.card :noPadding="true">
    {{-- Mobile Card View --}}
    <div class="block md:hidden p-4 space-y-3">
        @forelse($items as $item)
            <div class="bg-gray-50 rounded-lg p-3 flex items-center gap-3">
                @if($item['type'] === 'folder')
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                        </svg>
                    </div>
                @else
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        @if(in_array($item['extension'] ?? '', ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <img src="{{ $item['url'] }}" alt="{{ $item['name'] }}" class="w-10 h-10 object-cover rounded-lg">
                        @else
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @endif
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    @if($item['type'] === 'folder')
                        <a href="{{ route('admin.storage.index', ['path' => $item['path']]) }}" class="font-medium text-gray-900 hover:text-emerald-600 truncate block">
                            {{ $item['name'] }}
                        </a>
                    @else
                        <p class="font-medium text-gray-900 truncate">{{ $item['name'] }}</p>
                        <p class="text-xs text-gray-500">{{ number_format($item['size'] / 1024, 1) }} KB</p>
                    @endif
                </div>
                <div class="flex items-center gap-1">
                    @if($item['type'] === 'file')
                        <a href="{{ route('admin.storage.download', ['file' => $item['path']]) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    @endif
                    <button onclick="openDeleteModal('{{ $item['path'] }}', '{{ $item['type'] }}', '{{ $item['name'] }}')" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">Folder kosong.</div>
        @endforelse
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden md:block">
        <x-admin.table :headers="['Nama', 'Ukuran', 'Terakhir Diubah', 'Aksi']">
            @forelse($items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($item['type'] === 'folder')
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M10 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                                    </svg>
                                </div>
                                <a href="{{ route('admin.storage.index', ['path' => $item['path']]) }}" class="font-medium text-gray-900 hover:text-emerald-600">
                                    {{ $item['name'] }}
                                </a>
                            @else
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    @if(in_array($item['extension'] ?? '', ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <img src="{{ $item['url'] }}" alt="{{ $item['name'] }}" class="w-10 h-10 object-cover">
                                    @elseif(($item['extension'] ?? '') === 'pdf')
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4zM8.5 13H10v4H8.5v-4zm2.5 0h1.5v4H11v-4zm2.5 0H15v4h-1.5v-4z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                                    <p class="text-xs text-gray-500 uppercase">{{ $item['extension'] ?? 'file' }}</p>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        @if($item['type'] === 'file')
                            {{ number_format($item['size'] / 1024, 1) }} KB
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ date('d M Y H:i', $item['modified']) }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            @if($item['type'] === 'file')
                                @if(in_array($item['extension'] ?? '', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf']))
                                    <a href="{{ $item['url'] }}" target="_blank" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="Preview">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                @endif
                                <a href="{{ route('admin.storage.download', ['file' => $item['path']]) }}" class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg" title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            @endif
                            <button onclick="openRenameModal('{{ $item['path'] }}', '{{ $item['type'] }}', '{{ $item['name'] }}')" class="p-1.5 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg" title="Rename">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button onclick="openDeleteModal('{{ $item['path'] }}', '{{ $item['type'] }}', '{{ $item['name'] }}')" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Folder kosong.</td></tr>
            @endforelse
        </x-admin.table>
    </div>
</x-admin.card>


{{-- Upload Modal --}}
<div id="uploadModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload File</h3>
        <form action="{{ route('admin.storage.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="path" value="{{ $path }}">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File</label>
                <input type="file" name="files[]" multiple required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-xs text-gray-500 mt-1">Maksimal 50MB per file</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg">Upload</button>
            </div>
        </form>
    </div>
</div>

{{-- Create Folder Modal --}}
<div id="folderModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Buat Folder Baru</h3>
        <form action="{{ route('admin.storage.create-folder') }}" method="POST">
            @csrf
            <input type="hidden" name="path" value="{{ $path }}">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Folder</label>
                <input type="text" name="folder_name" required pattern="[a-zA-Z0-9\-_]+" class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="nama-folder">
                <p class="text-xs text-gray-500 mt-1">Hanya huruf, angka, dash, dan underscore</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('folderModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Buat</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
        <p class="text-gray-600 mb-4">Yakin ingin menghapus <span id="deleteItemName" class="font-medium"></span>?</p>
        <form action="{{ route('admin.storage.delete') }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="item" id="deleteItemPath">
            <input type="hidden" name="type" id="deleteItemType">
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">Hapus</button>
            </div>
        </form>
    </div>
</div>

{{-- Rename Modal --}}
<div id="renameModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubah Nama</h3>
        <form action="{{ route('admin.storage.rename') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="old_name" id="renameOldName">
            <input type="hidden" name="type" id="renameItemType">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Baru</label>
                <input type="text" name="new_name" id="renameNewName" required class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('renameModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openDeleteModal(path, type, name) {
    document.getElementById('deleteItemPath').value = path;
    document.getElementById('deleteItemType').value = type;
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function openRenameModal(path, type, name) {
    document.getElementById('renameOldName').value = path;
    document.getElementById('renameItemType').value = type;
    document.getElementById('renameNewName').value = name;
    document.getElementById('renameModal').classList.remove('hidden');
}
</script>
@endsection
