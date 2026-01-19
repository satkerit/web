<x-frontend-layout>
    <x-slot:title>Karir - {{ config('app.name') }}</x-slot:title>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-700 via-primary-500 to-primary-600 py-16 md:py-20">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">Karir</h1>
                <p class="text-lg text-white/90 max-w-2xl mx-auto">Bergabunglah bersama kami dan kembangkan karir Anda di industri perbankan syariah</p>
            </div>
        </div>
    </section>

    <!-- Careers List -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-8 bg-white rounded-xl shadow-sm p-4 md:p-6">
                <form method="GET" class="flex flex-col gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari lowongan berdasarkan judul, departemen, atau lokasi..." class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Pekerjaan</label>
                            <select name="type" class="w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Semua Tipe</option>
                                <option value="full_time" {{ request('type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ request('type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                                <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>Magang</option>
                            </select>
                        </div>
                        @if($departments->count() > 0)
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                            <select name="department" class="w-full rounded-lg border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Semua Departemen</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="flex items-end">
                            <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                                Cari
                            </button>
                        </div>
                    </div>
                    @if(request('search') || request('type') || request('department'))
                    <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-gray-100">
                        <span class="text-sm text-gray-500">Filter aktif:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 text-sm rounded-full">
                                "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('type'))
                            @php
                                $typeLabels = ['full_time' => 'Full Time', 'part_time' => 'Part Time', 'contract' => 'Kontrak', 'internship' => 'Magang'];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 text-sm rounded-full">
                                {{ $typeLabels[request('type')] ?? request('type') }}
                            </span>
                        @endif
                        @if(request('department'))
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 text-sm rounded-full">
                                {{ request('department') }}
                            </span>
                        @endif
                        <a href="{{ route('careers.index') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">
                            Reset semua
                        </a>
                    </div>
                    @endif
                </form>
            </div>

            <!-- Job Listings -->
            @if($careers->count() > 0)
                <div class="grid gap-6">
                    @foreach($careers as $career)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-primary-100 text-primary-700">
                                            {{ $career->employment_type_label }}
                                        </span>
                                        @if($career->deadline)
                                            <span class="text-xs text-gray-500">
                                                Deadline: {{ $career->deadline->format('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $career->title }}</h3>
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                        @if($career->department)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                {{ $career->department }}
                                            </span>
                                        @endif
                                        @if($career->location)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $career->location }}
                                            </span>
                                        @endif
                                        @if($career->salary_range)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $career->salary_range }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-3 text-gray-600 line-clamp-2">{{ Str::limit(strip_tags($career->description), 150) }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('careers.show', $career->slug) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                                        Lihat Detail
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($careers->hasPages())
                    <div class="mt-8">
                        {{ $careers->appends(request()->query())->links('pagination.custom') }}
                    </div>
                @endif
            @else
                <div class="text-center py-16 bg-white rounded-xl">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Lowongan</h3>
                    <p class="text-gray-600">Saat ini belum ada lowongan yang tersedia. Silakan cek kembali nanti.</p>
                </div>
            @endif
        </div>
    </section>
</x-frontend-layout>
