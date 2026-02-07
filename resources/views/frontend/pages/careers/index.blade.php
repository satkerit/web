<x-frontend-layout>
    <x-slot:title>Karir - {{ config('app.name') }}</x-slot:title>

    <!-- Hero Section -->
    <section class="relative py-20 md:py-24 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">Karir</h1>
            <p class="text-xl text-emerald-50 max-w-2xl mx-auto">Bergabunglah bersama kami dan kembangkan karir Anda di industri perbankan syariah yang terus bertumbuh.</p>
        </div>
    </section>

    <!-- Careers List -->
    <section class="py-12 md:py-20 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-10 bg-white rounded-2xl shadow-xl shadow-gray-200/50 p-6 md:p-8 border border-gray-100 transform -mt-24 relative z-10">
                <form method="GET" class="flex flex-col gap-6">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pencarian</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari posisi, departemen, atau kata kunci..." class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Pekerjaan</label>
                            <div class="relative">
                                <select name="type" class="block w-full pl-4 pr-10 py-3 bg-gray-50 border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 text-sm appearance-none">
                                    <option value="">Semua Tipe</option>
                                    <option value="full_time" {{ request('type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part_time" {{ request('type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                                    <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>Magang</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        @if($departments->count() > 0)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Departemen</label>
                            <div class="relative">
                                <select name="department" class="block w-full pl-4 pr-10 py-3 bg-gray-50 border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 text-sm appearance-none">
                                    <option value="">Semua Departemen</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-emerald-600/30 transition-all duration-300 transform hover:-translate-y-0.5">
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                    @if(request('search') || request('type') || request('department'))
                    <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Filter aktif:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-sm font-medium rounded-full border border-emerald-100">
                                "{{ request('search') }}"
                                <a href="{{ route('careers.index', array_merge(request()->except('search'))) }}" class="hover:text-emerald-900"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></a>
                            </span>
                        @endif
                        @if(request('type'))
                            @php
                                $typeLabels = ['full_time' => 'Full Time', 'part_time' => 'Part Time', 'contract' => 'Kontrak', 'internship' => 'Magang'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-sm font-medium rounded-full border border-emerald-100">
                                {{ $typeLabels[request('type')] ?? request('type') }}
                                <a href="{{ route('careers.index', array_merge(request()->except('type'))) }}" class="hover:text-emerald-900"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></a>
                            </span>
                        @endif
                        @if(request('department'))
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-sm font-medium rounded-full border border-emerald-100">
                                {{ request('department') }}
                                <a href="{{ route('careers.index', array_merge(request()->except('department'))) }}" class="hover:text-emerald-900"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></a>
                            </span>
                        @endif
                        <a href="{{ route('careers.index') }}" class="text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors ml-auto">
                            Reset Filter
                        </a>
                    </div>
                    @endif
                </form>
            </div>

            <!-- Job Listings -->
            @if($careers->count() > 0)
                <div class="grid gap-6">
                    @foreach($careers as $career)
                        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 p-6 md:p-8 border border-gray-100 group relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none translate-x-10 -translate-y-10"></div>
                            
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 relative z-10">
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-3 mb-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            {{ $career->employment_type_label }}
                                        </span>
                                        @if($career->deadline)
                                            <span class="inline-flex items-center text-xs font-medium text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Deadline: {{ $career->deadline->format('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-emerald-700 transition-colors">{{ $career->title }}</h3>
                                    <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-sm text-slate-600 mb-4">
                                        @if($career->department)
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                {{ $career->department }}
                                            </span>
                                        @endif
                                        @if($career->location)
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $career->location }}
                                            </span>
                                        @endif
                                        @if($career->salary_range)
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $career->salary_range }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-slate-600 line-clamp-2 leading-relaxed">{{ Str::limit(strip_tags($career->description), 150) }}</p>
                                </div>
                                <div class="flex-shrink-0 pt-4 md:pt-0">
                                    <a href="{{ route('careers.show', $career->slug) }}" class="inline-flex items-center justify-center w-full md:w-auto px-6 py-3 bg-white text-emerald-600 font-semibold rounded-xl border border-emerald-200 hover:bg-emerald-50 hover:border-emerald-300 transition-all duration-300 group-hover:shadow-md">
                                        Lihat Detail
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($careers->hasPages())
                    <div class="mt-12">
                        {{ $careers->appends(request()->query())->links('pagination.custom') }}
                    </div>
                @endif
            @else
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold tracking-tight text-slate-900 mb-2">Belum Ada Lowongan</h3>
                    <p class="text-slate-500 max-w-sm mx-auto">Saat ini belum ada posisi yang tersedia. Silakan cek kembali nanti atau ikuti media sosial kami untuk update terbaru.</p>
                </div>
            @endif
        </div>
    </section>
</x-frontend-layout>
