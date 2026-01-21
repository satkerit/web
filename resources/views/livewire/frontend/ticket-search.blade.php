<div class="w-full">
    <!-- Search Form -->
    <div class="relative">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live="ticketNumber"
                    wire:keydown.enter="search"
                    placeholder="Masukkan nomor tiket (contoh: {{ $type === 'whistleblowing' ? 'WBS-20260105-ABC123' : 'ADU-20260105-ABC123' }})"
                    class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-gray-700 placeholder-gray-400"
                >
            </div>
            <button
                wire:click="search"
                wire:loading.attr="disabled"
                class="px-8 py-4 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-emerald-600 transition-all duration-200 shadow-lg shadow-teal-500/30 hover:shadow-xl hover:shadow-teal-500/40 disabled:opacity-50 flex items-center justify-center gap-2"
            >
                <span wire:loading.remove wire:target="search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <span wire:loading wire:target="search">
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
                <span class="hidden sm:inline">Lacak</span>
            </button>
        </div>
    </div>

    <!-- Error Message -->
    @if($error)
    <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3" x-data x-init="$el.classList.add('animate-fade-in')">
        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="font-medium text-red-800">Tiket Tidak Ditemukan</p>
            <p class="text-sm text-red-600 mt-1">{{ $error }}</p>
        </div>
    </div>
    @endif

    <!-- Result Card -->
    @if($result)
    <div class="mt-6" x-data x-init="$el.classList.add('animate-fade-in')">
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl border border-gray-200 overflow-hidden shadow-xl">
            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-500 to-emerald-500 px-6 py-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-teal-100 text-sm font-medium">Nomor Tiket</p>
                        <p class="text-white text-xl font-bold tracking-wide font-mono">{{ $result['ticket_number'] }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $statusColors = [
                                'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'in_review' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                'investigating' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'resolved' => 'bg-green-100 text-green-800 border-green-200',
                                'closed' => 'bg-gray-100 text-gray-800 border-gray-200',
                            ];
                            $statusColor = $statusColors[$result['status']] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        @endphp
                        <span class="px-4 py-2 rounded-full text-sm font-semibold border {{ $statusColor }}">
                            {{ $result['status_label'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Subject -->
                <div class="mb-6">
                    <h4 class="text-lg font-bold text-gray-900">{{ $result['subject'] }}</h4>
                    <p class="text-sm text-gray-500 mt-1">
                        @if(isset($result['category']))
                            Kategori: {{ $result['category'] }}
                        @elseif(isset($result['type']))
                            Jenis: {{ $result['type'] }}
                        @endif
                    </p>
                </div>

                <!-- Timeline -->
                <div class="grid sm:grid-cols-2 gap-4 mb-6">
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Pengaduan</p>
                            <p class="font-semibold text-gray-900">{{ $result['created_at'] }}</p>
                        </div>
                    </div>
                    @if($result['resolved_at'])
                    <div class="flex items-center gap-3 p-4 bg-green-50 rounded-xl">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Selesai</p>
                            <p class="font-semibold text-green-700">{{ $result['resolved_at'] }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Resolution (if available) -->
                @if(isset($result['resolution']) && $result['resolution'])
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-emerald-800 text-sm">Resolusi</p>
                            <p class="text-emerald-700 mt-1">{{ $result['resolution'] }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Status Progress -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-sm font-medium text-gray-700 mb-4">Progress Pengaduan</p>
                    @php
                        $statuses = $type === 'whistleblowing'
                            ? ['pending' => 'Menunggu', 'in_review' => 'Review', 'investigating' => 'Investigasi', 'resolved' => 'Selesai']
                            : ['pending' => 'Menunggu', 'in_progress' => 'Diproses', 'resolved' => 'Selesai'];
                        $currentIndex = array_search($result['status'], array_keys($statuses));
                        if ($result['status'] === 'closed') $currentIndex = count($statuses) - 1;
                    @endphp
                    <div class="flex items-center justify-between">
                        @foreach($statuses as $key => $label)
                            @php
                                $index = array_search($key, array_keys($statuses));
                                $isActive = $index <= $currentIndex;
                                $isCurrent = $key === $result['status'] || ($result['status'] === 'closed' && $key === 'resolved');
                            @endphp
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isActive ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-400' }} {{ $isCurrent ? 'ring-4 ring-teal-100' : '' }}">
                                    @if($isActive)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <span class="text-xs font-bold">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <span class="text-xs mt-2 {{ $isActive ? 'text-teal-600 font-medium' : 'text-gray-400' }}">{{ $label }}</span>
                            </div>
                            @if(!$loop->last)
                                <div class="flex-1 h-1 mx-2 {{ $index < $currentIndex ? 'bg-teal-500' : 'bg-gray-200' }} rounded-full" style="max-width: 60px;"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <button
                    wire:click="resetSearch"
                    class="text-sm text-teal-600 hover:text-teal-700 font-medium flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Cari tiket lain
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
