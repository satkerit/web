@extends('layouts.admin')

@section('title', 'Detail Pengaduan Nasabah')

@section('content')
<x-admin.page-header title="Detail Pengaduan" :subtitle="$customerComplaint->ticket_number">
    <x-slot:actions>
        <a href="{{ route('admin.customer-complaints.print-single', $customerComplaint) }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak
        </a>
        <x-admin.button href="{{ route('admin.customer-complaints.index') }}" variant="secondary">Kembali</x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <x-admin.card title="Detail Pengaduan">
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Subjek</p>
                    <p class="font-medium text-gray-900">{{ $customerComplaint->subject }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Deskripsi</p>
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $customerComplaint->description }}</p>
                </div>
                @if($customerComplaint->branch_office)
                    <div>
                        <p class="text-sm text-gray-500">Kantor Terkait</p>
                        <p class="text-gray-900">{{ $customerComplaint->branch_office }}</p>
                    </div>
                @endif
                @if($customerComplaint->incident_date)
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Kejadian</p>
                        <p class="text-gray-900">{{ $customerComplaint->incident_date->format('d M Y') }}</p>
                    </div>
                @endif
            </div>
        </x-admin.card>

        @if($customerComplaint->attachments && count($customerComplaint->attachments) > 0)
            <x-admin.card title="Lampiran">
                <div class="space-y-2">
                    @foreach($customerComplaint->attachments as $attachment)
                        <a href="{{ \App\Helpers\StorageHelper::url($attachment) }}" target="_blank" class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <span class="text-sm text-gray-600">{{ basename($attachment) }}</span>
                        </a>
                    @endforeach
                </div>
            </x-admin.card>
        @endif

        <x-admin.card title="Update Status & Resolusi">
            <form action="{{ route('admin.customer-complaints.update', $customerComplaint) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="pending" {{ $customerComplaint->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="in_progress" {{ $customerComplaint->status == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                                <option value="resolved" {{ $customerComplaint->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                <option value="closed" {{ $customerComplaint->status == 'closed' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                            <select name="priority" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="low" {{ $customerComplaint->priority == 'low' ? 'selected' : '' }}>Rendah</option>
                                <option value="medium" {{ $customerComplaint->priority == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="high" {{ $customerComplaint->priority == 'high' ? 'selected' : '' }}>Tinggi</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Resolusi/Penyelesaian</label>
                        <textarea name="resolution" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Jelaskan penyelesaian yang diberikan...">{{ old('resolution', $customerComplaint->resolution) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Internal</label>
                        <textarea name="admin_notes" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Catatan internal (tidak dilihat nasabah)...">{{ old('admin_notes', $customerComplaint->admin_notes) }}</textarea>
                    </div>
                    <x-admin.button type="submit">Simpan Perubahan</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>

    <div class="space-y-6">
        <x-admin.card title="Data Nasabah">
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="font-medium text-gray-900">{{ $customerComplaint->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-gray-900">{{ $customerComplaint->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Telepon</p>
                    <p class="text-gray-900">{{ $customerComplaint->phone }}</p>
                </div>
                @if($customerComplaint->account_number)
                    <div>
                        <p class="text-sm text-gray-500">No. Rekening</p>
                        <p class="text-gray-900">{{ $customerComplaint->account_number }}</p>
                    </div>
                @endif
            </div>
        </x-admin.card>

        <x-admin.card title="Status Pengaduan">
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Kategori</p>
                    <x-admin.badge>{{ $customerComplaint->category_label }}</x-admin.badge>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Prioritas</p>
                    @switch($customerComplaint->priority)
                        @case('high')
                            <x-admin.badge variant="danger">Tinggi</x-admin.badge>
                            @break
                        @case('medium')
                            <x-admin.badge variant="warning">Sedang</x-admin.badge>
                            @break
                        @case('low')
                            <x-admin.badge variant="info">Rendah</x-admin.badge>
                            @break
                    @endswitch
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    @switch($customerComplaint->status)
                        @case('pending')
                            <x-admin.badge variant="warning">Menunggu</x-admin.badge>
                            @break
                        @case('in_progress')
                            <x-admin.badge variant="info">Diproses</x-admin.badge>
                            @break
                        @case('resolved')
                            <x-admin.badge variant="success">Selesai</x-admin.badge>
                            @break
                        @case('closed')
                            <x-admin.badge>Ditutup</x-admin.badge>
                            @break
                    @endswitch
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Pengaduan</p>
                    <p class="text-gray-900">{{ $customerComplaint->created_at->format('d M Y H:i') }}</p>
                </div>
                @if($customerComplaint->handler)
                    <div>
                        <p class="text-sm text-gray-500">Ditangani Oleh</p>
                        <p class="text-gray-900">{{ $customerComplaint->handler->name }}</p>
                    </div>
                @endif
                @if($customerComplaint->resolved_at)
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Selesai</p>
                        <p class="text-gray-900">{{ $customerComplaint->resolved_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </x-admin.card>

        @if($customerComplaint->resolution)
            <x-admin.card title="Resolusi">
                <p class="text-gray-900 whitespace-pre-wrap">{{ $customerComplaint->resolution }}</p>
            </x-admin.card>
        @endif
    </div>
</div>
@endsection
