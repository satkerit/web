@extends('layouts.admin')

@section('title', isset($boardMember) ? 'Edit Anggota' : 'Tambah Anggota')

@section('content')
<x-admin.page-header :title="isset($boardMember) ? 'Edit Anggota' : 'Tambah Anggota'">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.board-members.index') }}" variant="secondary">Kembali</x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<form action="{{ isset($boardMember) ? route('admin.board-members.update', $boardMember) : route('admin.board-members.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($boardMember)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Informasi Anggota">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-admin.input name="name" label="Nama Lengkap" :value="old('name', $boardMember->name ?? '')" required :error="$errors->first('name')"/>
                        <x-admin.input name="position" label="Jabatan" :value="old('position', $boardMember->position ?? '')" required placeholder="Contoh: Komisaris Utama"/>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                            <select name="type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="komisaris" {{ old('type', $boardMember->type ?? '') == 'komisaris' ? 'selected' : '' }}>Dewan Komisaris</option>
                                <option value="direksi" {{ old('type', $boardMember->type ?? '') == 'direksi' ? 'selected' : '' }}>Dewan Direksi</option>
                                <option value="pengawas_syariah" {{ old('type', $boardMember->type ?? '') == 'pengawas_syariah' ? 'selected' : '' }}>Dewan Pengawas Syariah</option>
                            </select>
                        </div>
                        <x-admin.input type="number" name="order_position" label="Urutan" :value="old('order_position', $boardMember->order_position ?? 0)" min="0"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Biografi</label>
                        <textarea name="biography" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('biography', $boardMember->biography ?? '') }}</textarea>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Pendidikan">
                <div x-data='arrayItems(@js(old("education", $boardMember->education ?? [])))' class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-2">
                            <input type="text"
                                   :name="'education[' + index + ']'"
                                   x-model="items[index]"
                                   class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors"
                                   placeholder="Contoh: S1 Ekonomi - Universitas Indonesia">
                            <button type="button"
                                    @click="removeItem(index)"
                                    x-show="items.length > 1"
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <button type="button"
                            @click="addItem()"
                            class="text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                        + Tambah Pendidikan
                    </button>
                </div>
            </x-admin.card>

            <x-admin.card title="Pengalaman">
                <div x-data='arrayItems(@js(old("experience", $boardMember->experience ?? [])))' class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-2">
                            <input type="text"
                                   :name="'experience[' + index + ']'"
                                   x-model="items[index]"
                                   class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors"
                                   placeholder="Contoh: Direktur PT ABC (2015-2020)">
                            <button type="button"
                                    @click="removeItem(index)"
                                    x-show="items.length > 1"
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <button type="button"
                            @click="addItem()"
                            class="text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                        + Tambah Pengalaman
                    </button>
                </div>
            </x-admin.card>
        </div>

        <div class="space-y-6">
            <x-admin.card title="Foto">
                <x-admin.image-picker
                    name="photo"
                    :value="$boardMember->photo ?? null"
                    hint="Rekomendasi: 400x500px. Format: JPG, PNG, WebP. Maks 2MB"
                    previewClass="w-full h-48 object-cover"
                />
            </x-admin.card>

            <x-admin.button type="submit" class="w-full">
                {{ isset($boardMember) ? 'Simpan Perubahan' : 'Tambah Anggota' }}
            </x-admin.button>
        </div>
    </div>
</form>
@endsection
