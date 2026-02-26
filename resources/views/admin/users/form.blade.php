@extends('layouts.admin')

@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
<x-admin.page-header :title="isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna'">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.users.index') }}" variant="secondary">Kembali</x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="max-w-2xl">
    <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
        @csrf
        @if(isset($user)) @method('PUT') @endif

        <x-admin.card title="Informasi Pengguna">
            <div class="space-y-4">
                <x-admin.input name="name" label="Nama Lengkap" :value="old('name', $user->name ?? '')" required :error="$errors->first('name')"/>

                <x-admin.input type="email" name="email" label="Email" :value="old('email', $user->email ?? '')" required :error="$errors->first('email')"/>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-admin.input type="password" name="password" label="Password" :required="!isset($user)" :error="$errors->first('password')" :hint="isset($user) ? 'Kosongkan jika tidak ingin mengubah password' : ''"/>
                    </div>
                    <div>
                        <x-admin.input type="password" name="password_confirmation" label="Konfirmasi Password" :required="!isset($user)"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                        <select name="role_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $roleModel)
                                <option value="{{ $roleModel->id }}" {{ old('role_id', $user->role_id ?? '') == $roleModel->id ? 'selected' : '' }}>{{ $roleModel->display_name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Pilih role dengan permission yang sesuai</p>
                        @error('role_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_active" class="text-sm text-gray-700">Akun Aktif</label>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <x-admin.button type="submit">
                        {{ isset($user) ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                    </x-admin.button>
                </div>
            </div>
        </x-admin.card>
    </form>
</div>
@endsection
