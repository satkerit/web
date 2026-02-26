@extends('layouts.admin')

@section('title', 'Kelola IP Terblokir')

@section('content')
<x-admin.page-header title="Kelola IP Terblokir" subtitle="Daftar IP yang diblokir oleh sistem">
    <x-slot:actions>
        <button @click="$dispatch('open-modal', 'block-ip')" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
            Blokir IP Manual
        </button>
        <form action="{{ route('admin.settings.security.clear-expired') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-xl hover:bg-orange-700 transition-colors font-medium"
                    onclick="return confirm('Hapus semua blokir yang sudah kadaluarsa?')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Kadaluarsa
            </button>
        </form>
        <x-admin.button href="{{ route('admin.settings.security') }}" variant="secondary">
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
    {{ session('success') }}
</div>
@endif

<x-admin.card>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attempts</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Blocked Until</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($blockedIps as $block)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-gray-900">
                        {{ $block->ip_address }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $block->reason ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $block->attempts }} attempts
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        @if($block->is_permanent)
                        <span class="text-red-600 font-medium">Permanent</span>
                        @else
                        {{ $block->blocked_until ? $block->blocked_until->format('d M Y H:i') : '-' }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($block->is_permanent)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Permanent
                        </span>
                        @else
                        @if($block->blocked_until && $block->blocked_until->isPast())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Expired
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            Temporary
                        </span>
                        @endif
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $block->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <form action="{{ route('admin.settings.security.unblock', $block) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-blue-600 hover:text-blue-900"
                                    onclick="return confirm('Unblock IP {{ $block->ip_address }}?')">
                                Unblock
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        Tidak ada IP yang terblokir
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($blockedIps->hasPages())
    <div class="mt-6">
        {{ $blockedIps->links() }}
    </div>
    @endif
</x-admin.card>

<!-- Block IP Modal -->
<div x-data="{ open: false }" 
     @open-modal.window="open = ($event.detail === 'block-ip')"
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="open" @click="open = false" class="fixed inset-0 bg-black bg-opacity-50"></div>
        
        <div x-show="open" class="relative bg-white rounded-2xl max-w-2xl w-full p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Blokir IP Manual</h3>
            
            <form action="{{ route('admin.settings.security.block-ip') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        IP Address
                    </label>
                    <input type="text" name="ip_address" required
                           placeholder="192.168.1.1"
                           class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 font-mono">
                    <p class="mt-1 text-xs text-gray-500">Masukkan IP address yang ingin diblokir</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Alasan
                    </label>
                    <input type="text" name="reason"
                           placeholder="Contoh: Suspicious activity, Brute force attempt"
                           class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_permanent" id="is_permanent" value="1"
                           x-data="{ checked: false }"
                           x-model="checked"
                           @change="document.getElementById('duration_field').style.display = checked ? 'none' : 'block'"
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_permanent" class="ml-2 text-sm text-gray-700">Blokir Permanen</label>
                </div>
                
                <div id="duration_field">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Durasi (jam)
                    </label>
                    <input type="number" name="duration_hours" value="24" min="1" max="168"
                           class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Durasi pemblokiran dalam jam (1-168 jam / 1-7 hari)</p>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
                        Blokir IP
                    </button>
                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
