<div x-data="prayerTimeWidget()" x-init="init()" class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden">
    <!-- Compact Header -->
    <div class="bg-white/10 backdrop-blur-sm px-4 py-3 border-b border-white/20">
        <!-- Drag Handle for Mobile -->
        <div class="xl:hidden flex justify-center mb-2">
            <div class="w-12 h-1 bg-white/30 rounded-full"></div>
        </div>
        
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-sm">Jadwal Sholat</h3>
                    <p class="text-white/70 text-xs" x-text="location"></p>
                </div>
            </div>
            <button @click="getUserLocation()" class="p-1.5 hover:bg-white/10 rounded-lg transition-colors" title="Perbarui Lokasi">
                <svg class="w-4 h-4 text-white" :class="{'animate-spin': loading}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Compact Current Time -->
    <div class="px-4 py-2 bg-white/5 border-b border-white/10">
        <div class="text-center">
            <div class="text-xl font-bold text-white" x-text="currentTime"></div>
            <div class="text-white/70 text-xs" x-text="currentDate"></div>
        </div>
    </div>

    <!-- Compact Next Prayer Countdown -->
    <div x-show="nextPrayer" class="px-4 py-3 bg-white/10 border-b border-white/10">
        <div class="text-center">
            <p class="text-white/70 text-xs mb-1">Menuju</p>
            <p class="text-white font-bold text-sm mb-2" x-text="nextPrayer?.name"></p>
            <div class="flex items-center justify-center gap-1.5">
                <div class="bg-white/20 rounded-lg px-2 py-1 min-w-[45px]">
                    <div class="text-lg font-bold text-white" x-text="countdown.hours"></div>
                    <div class="text-white/70 text-[10px]">Jam</div>
                </div>
                <div class="text-white text-sm">:</div>
                <div class="bg-white/20 rounded-lg px-2 py-1 min-w-[45px]">
                    <div class="text-lg font-bold text-white" x-text="countdown.minutes"></div>
                    <div class="text-white/70 text-[10px]">Menit</div>
                </div>
                <div class="text-white text-sm">:</div>
                <div class="bg-white/20 rounded-lg px-2 py-1 min-w-[45px]">
                    <div class="text-lg font-bold text-white" x-text="countdown.seconds"></div>
                    <div class="text-white/70 text-[10px]">Detik</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Prayer Times List -->
    <div class="px-3 py-3">
        <template x-if="loading">
            <div class="text-center py-6">
                <div class="inline-block animate-spin rounded-full h-6 w-6 border-3 border-white/30 border-t-white"></div>
                <p class="text-white/80 text-xs mt-2">Memuat...</p>
            </div>
        </template>

        <template x-if="!loading && error">
            <div class="text-center py-4">
                <p class="text-white/90 text-xs mb-2" x-text="error"></p>
                <button @click="fetchPrayerTimes()" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white rounded-lg text-xs transition-colors">
                    Coba Lagi
                </button>
            </div>
        </template>

        <template x-if="!loading && !error && prayerTimes.length > 0">
            <div class="space-y-1.5">
                <template x-for="prayer in prayerTimes" :key="prayer.name">
                    <div class="flex items-center justify-between p-2 rounded-lg transition-all"
                         :class="prayer.isNext ? 'bg-white/20 ring-1 ring-white/40' : 'bg-white/5 hover:bg-white/10'">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                                 :class="prayer.isNext ? 'bg-white/30' : 'bg-white/10'">
                                <span class="text-white text-sm" x-text="prayer.icon"></span>
                            </div>
                            <span class="text-white font-medium text-sm" x-text="prayer.name"></span>
                        </div>
                        <div class="text-right">
                            <div class="text-white font-bold text-sm" x-text="prayer.time"></div>
                            <div x-show="prayer.isNext" class="text-white/80 text-[10px]">Selanjutnya</div>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <!-- Compact Footer -->
    <div class="px-3 py-2 bg-white/5 border-t border-white/10">
        <p class="text-white/60 text-[10px] text-center">
            Diperbarui otomatis setiap hari
        </p>
    </div>
</div>
