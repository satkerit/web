@extends('layouts.admin')

@section('title', 'Konfigurasi Simulasi Pembiayaan')

@section('content')
<x-admin.page-header title="Konfigurasi Simulasi Pembiayaan" subtitle="Kelola parameter perhitungan simulasi pembiayaan">
    <x-slot:actions>
        <a href="{{ route('admin.financing-config.create') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-300">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pembiayaan
        </a>
    </x-slot:actions>
</x-admin.page-header>

{{-- Info Card --}}
<div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
    <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="text-sm text-blue-800">
            <p class="font-medium mb-1">Tentang Simulasi Pembiayaan</p>
            <p class="text-blue-700">Konfigurasi ini digunakan untuk menghitung estimasi angsuran pada halaman simulasi pembiayaan. Formula yang digunakan adalah flat rate: <code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">(Pokok + (Pokok × Margin × Tenor/12)) / Tenor</code></p>
        </div>
    </div>
</div>

<x-admin.card :noPadding="true">
    {{-- Desktop Table View --}}
    <div class="hidden lg:block">
        <x-admin.table :headers="['Jenis Pembiayaan', 'Margin Rate', 'Plafon', 'DP', 'Status', 'Aksi']">
            @forelse($configs as $config)
                <tr class="group hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $config->name }}</p>
                            <p class="text-sm text-slate-500">{{ $config->type }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-emerald-600">{{ number_format($config->margin_rate * 100, 2) }}%</span>
                        <p class="text-xs text-slate-500">per tahun</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">
                            <p class="text-slate-600">Min: <span class="font-medium text-slate-900">Rp {{ number_format($config->min_principal, 0, ',', '.') }}</span></p>
                            <p class="text-slate-600">Max: <span class="font-medium text-slate-900">Rp {{ number_format($config->max_principal, 0, ',', '.') }}</span></p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($config->dp_enabled)
                            <x-admin.badge variant="info">Aktif</x-admin.badge>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $config->dp_min_percentage ?? 0 }}% - {{ $config->dp_max_percentage ?? 100 }}%
                            </p>
                        @else
                            <span class="text-slate-400 text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($config->is_active)
                            <x-admin.badge variant="success">Aktif</x-admin.badge>
                        @else
                            <x-admin.badge variant="danger">Nonaktif</x-admin.badge>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.financing-config.edit', $config) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all inline-flex" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.financing-config.destroy', $config) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konfigurasi pembiayaan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all inline-flex" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">Belum ada konfigurasi pembiayaan</p>
                        <p class="text-sm text-slate-400 mt-1">Jalankan seeder untuk menambahkan konfigurasi default</p>
                    </td>
                </tr>
            @endforelse
        </x-admin.table>
    </div>

    {{-- Mobile Card View --}}
    <div class="block lg:hidden p-4 space-y-4">
        @forelse($configs as $config)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $config->name }}</h3>
                            <p class="text-sm text-slate-500">{{ $config->type }}</p>
                        </div>
                        @if($config->is_active)
                            <x-admin.badge variant="success">Aktif</x-admin.badge>
                        @else
                            <x-admin.badge variant="danger">Nonaktif</x-admin.badge>
                        @endif
                    </div>

                    <div class="space-y-2 mb-4 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Margin Rate:</span>
                            <span class="font-semibold text-emerald-600">{{ number_format($config->margin_rate * 100, 2) }}% / tahun</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Plafon Min:</span>
                            <span class="font-medium text-slate-900">Rp {{ number_format($config->min_principal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Plafon Max:</span>
                            <span class="font-medium text-slate-900">Rp {{ number_format($config->max_principal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Down Payment:</span>
                            @if($config->dp_enabled)
                                <span class="font-medium text-blue-600">{{ $config->dp_min_percentage ?? 0 }}% - {{ $config->dp_max_percentage ?? 100 }}%</span>
                            @else
                                <span class="text-slate-400">Tidak aktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs text-slate-500 mb-2">Tenor Tersedia:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($config->available_tenors as $tenor)
                                <span class="inline-flex px-2 py-0.5 bg-slate-100 text-slate-700 text-xs font-medium rounded-lg">
                                    {{ $tenor }} bln
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('admin.financing-config.edit', $config) }}" class="flex items-center justify-center gap-2 py-2.5 text-sm font-semibold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors w-full mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Konfigurasi
                    </a>
                    <form action="{{ route('admin.financing-config.destroy', $config) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konfigurasi pembiayaan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center justify-center gap-2 py-2.5 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-1">Belum Ada Konfigurasi</h3>
                <p class="text-slate-500">Jalankan seeder untuk menambahkan konfigurasi default</p>
            </div>
        @endforelse
    </div>
</x-admin.card>
@endsection
