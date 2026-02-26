@extends('layouts.admin')

@section('title', 'Why Choose Us')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Why Choose Us</h2>
                <p class="text-slate-500 mt-1">Kelola poin-poin keunggulan perusahaan Anda.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.why-choose-us.settings') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-white border border-slate-200 rounded-xl font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition-all shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Pengaturan Section
                </a>
                <a href="{{ route('admin.why-choose-us.create') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-teal-500 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Item
                </a>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($items as $item)
                <div
                    class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-300 border border-slate-100 relative overflow-hidden flex flex-col h-full">
                    <!-- Color Bar -->
                    <div class="absolute top-0 left-0 w-full h-1 {{ $item->bg_class }}"></div>

                    <div class="flex justify-between items-start mb-5">
                        <div
                            class="w-14 h-14 rounded-2xl {{ $item->bg_class }} flex items-center justify-center {{ $item->text_class }} shadow-sm">
                            @if($item->icon && !Str::startsWith($item->icon, ['http', 'https']))
                                <img src="{{ \App\Helpers\StorageHelper::url($item->icon) }}" class="w-7 h-7 object-contain" alt="">
                            @else
                                <!-- Default Icon fallback logic or SVG -->
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @endif
                        </div>
                        <div
                            class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-2 group-hover:translate-x-0 duration-300">
                            <a href="{{ route('admin.why-choose-us.edit', $item->id) }}"
                                class="p-2 text-slate-400 hover:text-blue-500 hover:bg-green-50 rounded-lg transition-colors"
                                title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.why-choose-us.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $item->title }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-4">{{ $item->description }}</p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-50 mt-4">
                        <span
                            class="text-xs font-semibold px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 border border-slate-200">
                            Urutan: {{ $item->sort_order }}
                        </span>
                        <span
                            class="text-xs font-semibold px-2.5 py-1 rounded-md {{ $item->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-slate-50 text-slate-500 border border-slate-100' }}">
                            {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-20 text-center bg-white rounded-3xl border-2 border-dashed border-slate-200 hover:border-green-200 transition-colors group">
                    <div
                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Belum ada item</h3>
                    <p class="text-slate-500 mt-2 max-w-sm mx-auto">Mulai dengan menambahkan poin keunggulan perusahaan Anda
                        untuk ditampilkan di halaman depan.</p>
                    <div class="mt-8">
                        <a href="{{ route('admin.why-choose-us.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-500 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:bg-blue-600 hover:-translate-y-1 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Item Pertama
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
