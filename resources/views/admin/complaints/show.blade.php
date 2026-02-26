@extends('layouts.admin')

@section('title', 'Detail Pengaduan')

@section('content')
<x-admin.page-header title="Detail Pengaduan" :subtitle="$complaint->ticket_number">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.complaints.index') }}" variant="secondary">Kembali</x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <x-admin.card title="Informasi Pengaduan">
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Subjek</p>
                    <p class="font-medium text-gray-900">{{ $complaint->subject }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Deskripsi</p>
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $complaint->description }}</p>
                </div>
                @if($complaint->reported_person)
                    <div>
                        <p class="text-sm text-gray-500">Pihak yang Dilaporkan</p>
                        <p class="text-gray-900">{{ $complaint->reported_person }}</p>
                    </div>
                @endif
                @if($complaint->reported_department)
                    <div>
                        <p class="text-sm text-gray-500">Departemen</p>
                        <p class="text-gray-900">{{ $complaint->reported_department }}</p>
                    </div>
                @endif
                @if($complaint->incident_date)
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Kejadian</p>
                        <p class="text-gray-900">{{ $complaint->incident_date->format('d M Y') }}</p>
                    </div>
                @endif
                @if($complaint->incident_location)
                    <div>
                        <p class="text-sm text-gray-500">Lokasi Kejadian</p>
                        <p class="text-gray-900">{{ $complaint->incident_location }}</p>
                    </div>
                @endif
            </div>
        </x-admin.card>

        @if($complaint->attachments && count($complaint->attachments) > 0)
            <x-admin.card title="Lampiran">
                <div class="space-y-2">
                    @foreach($complaint->attachments as $attachment)
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

        <x-admin.card title="Update Status">
            <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="in_review" {{ $complaint->status == 'in_review' ? 'selected' : '' }}>Dalam Review</option>
                            <option value="investigating" {{ $complaint->status == 'investigating' ? 'selected' : '' }}>Investigasi</option>
                            <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                            <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>Ditutup</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin</label>
                        <textarea name="admin_notes" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('admin_notes', $complaint->admin_notes) }}</textarea>
                    </div>
                    <x-admin.button type="submit">Update Status</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>

    <div class="space-y-6">
        <x-admin.card title="Informasi Pelapor">
            <div class="space-y-3">
                @if($complaint->is_anonymous)
                    <p class="text-gray-500 italic">Pelapor memilih untuk anonim</p>
                @else
                    <div>
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-medium text-gray-900">{{ $complaint->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-gray-900">{{ $complaint->email }}</p>
                    </div>
                    @if($complaint->phone)
                        <div>
                            <p class="text-sm text-gray-500">Telepon</p>
                            <p class="text-gray-900">{{ $complaint->phone }}</p>
                        </div>
                    @endif
                    @if($complaint->identity_number)
                        <div>
                            <p class="text-sm text-gray-500">No. Identitas</p>
                            <p class="text-gray-900">{{ $complaint->identity_number }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </x-admin.card>

        <x-admin.card title="Status">
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Tipe Pengaduan</p>
                    <x-admin.badge>{{ $complaint->type_label }}</x-admin.badge>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status Saat Ini</p>
                    @switch($complaint->status)
                        @case('pending')
                            <x-admin.badge variant="warning">Menunggu</x-admin.badge>
                            @break
                        @case('in_review')
                            <x-admin.badge variant="info">Dalam Review</x-admin.badge>
                            @break
                        @case('investigating')
                            <x-admin.badge variant="primary">Investigasi</x-admin.badge>
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
                    <p class="text-sm text-gray-500">Tanggal Laporan</p>
                    <p class="text-gray-900">{{ $complaint->created_at->format('d M Y H:i') }}</p>
                </div>
                @if($complaint->resolved_at)
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Selesai</p>
                        <p class="text-gray-900">{{ $complaint->resolved_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </x-admin.card>
    </div>
</div>
@endsection
