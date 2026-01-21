<div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5">
        <h2 class="text-xl font-bold text-white flex items-center">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Kalkulator Simulasi
        </h2>
        <p class="text-emerald-100 text-sm mt-1">Hitung estimasi angsuran pembiayaan Anda</p>
    </div>

    <!-- Form -->
    <form wire:submit="calculate" class="p-6 space-y-6">
        <!-- Financing Type -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Pilih Pembiayaan
            </label>
            <select
                wire:model.live="financingType"
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('financingType') border-red-300 @enderror"
            >
                <option value="">Pilih Pembiayaan</option>
                @foreach($configs as $config)
                    <option value="{{ $config->id }}">{{ $config->name }}</option>
                @endforeach
            </select>
            @if($selectedConfig)
                <p class="mt-1 text-xs text-gray-500">
                    Margin: {{ number_format($selectedConfig->margin_rate * 100, 2) }}% per tahun
                </p>
            @endif
            @error('financingType')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Principal Amount -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Jumlah Pembiayaan
            </label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                <input
                    type="text"
                    wire:model.live.blur="principal"
                    x-data="{
                        formatNumber(e) {
                            let value = e.target.value.replace(/[^0-9]/g, '');
                            if (value) {
                                e.target.value = new Intl.NumberFormat('id-ID').format(value);
                            }
                            $wire.set('principal', value);
                        }
                    }"
                    x-on:input="formatNumber($event)"
                    class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('principal') border-red-300 @enderror"
                    placeholder="Contoh: 50.000.000"
                >
            </div>
            @if($selectedConfig)
                <p class="mt-1 text-xs text-gray-500">
                    Min: Rp {{ number_format($selectedConfig->min_principal, 0, ',', '.') }} -
                    Max: Rp {{ number_format($selectedConfig->max_principal, 0, ',', '.') }}
                </p>
            @endif
            @error('principal')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tenor Selection -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Jangka Waktu (Bulan)
            </label>
            <div class="relative">
                <input
                    type="number"
                    wire:model.live="tenor"
                    min="1"
                    max="60"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('tenor') border-red-300 @enderror"
                    placeholder="Masukkan jangka waktu (1-60 bulan)"
                >
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">bulan</span>
            </div>
            <p class="mt-1 text-xs text-gray-500">Maksimal 60 bulan</p>
            @error('tenor')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Down Payment (if enabled) -->
        @if($selectedConfig && $selectedConfig->dp_enabled)
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Uang Muka (DP) <span class="text-gray-400 font-normal">- Opsional</span>
            </label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                <input
                    type="text"
                    wire:model.live.blur="downPayment"
                    x-data="{
                        formatNumber(e) {
                            let value = e.target.value.replace(/[^0-9]/g, '');
                            if (value) {
                                e.target.value = new Intl.NumberFormat('id-ID').format(value);
                            }
                            $wire.set('downPayment', value);
                        }
                    }"
                    x-on:input="formatNumber($event)"
                    class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('downPayment') border-red-300 @enderror"
                    placeholder="Contoh: 10.000.000"
                >
            </div>
            <p class="mt-1 text-xs text-gray-500">
                @if($selectedConfig->dp_min_percentage || $selectedConfig->dp_max_percentage)
                    DP: {{ $selectedConfig->dp_min_percentage ?? 0 }}% - {{ $selectedConfig->dp_max_percentage ?? 100 }}% dari jumlah pembiayaan
                @else
                    Masukkan jumlah uang muka jika ada
                @endif
            </p>
            @error('downPayment')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <button
                type="submit"
                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-300 hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="calculate">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Hitung Simulasi
                </span>
                <span wire:loading wire:target="calculate" class="flex items-center">
                    <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menghitung...
                </span>
            </button>
            <button
                type="button"
                wire:click="resetCalculator"
                class="px-6 py-3 border-2 border-gray-200 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 transition-all duration-200"
            >
                Reset
            </button>
        </div>
    </form>

    <!-- Result Section -->
    @if($result)
        <div class="border-t border-gray-100 bg-gradient-to-br from-emerald-50 to-teal-50 p-6"
             x-data="{ show: false }"
             x-init="setTimeout(() => show = true, 100)"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0">

            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Hasil Simulasi
            </h3>

            <!-- Financing Name Badge -->
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                    {{ $result['config_name'] ?? 'Pembiayaan' }}
                </span>
                <span class="text-sm text-gray-500 ml-2">Margin {{ number_format($result['margin_percentage'] ?? 0, 2) }}%/tahun</span>
            </div>

            <!-- Summary Card -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-emerald-100 mb-4">
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-1">Angsuran Per Bulan</p>
                    <p class="text-3xl font-bold text-emerald-600">
                        Rp {{ number_format($result['monthly_installment'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">selama {{ $result['tenor'] }} bulan</p>
                </div>
            </div>

            <!-- Detail Grid -->
            <div class="grid grid-cols-2 gap-3">
                @if(isset($result['down_payment']) && $result['down_payment'] > 0)
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Harga / Nilai Pembiayaan</p>
                    <p class="text-lg font-semibold text-gray-900">
                        Rp {{ number_format($result['original_principal'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-blue-100">
                    <p class="text-xs text-blue-600 mb-1">Uang Muka (DP {{ $result['dp_percentage'] }}%)</p>
                    <p class="text-lg font-semibold text-blue-600">
                        Rp {{ number_format($result['down_payment'], 0, ',', '.') }}
                    </p>
                </div>
                @endif
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Pokok Pembiayaan</p>
                    <p class="text-lg font-semibold text-gray-900">
                        Rp {{ number_format($result['principal'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Total Margin</p>
                    <p class="text-lg font-semibold text-gray-900">
                        Rp {{ number_format($result['total_margin'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 col-span-2">
                    <p class="text-xs text-gray-500 mb-1">Total Pembayaran (Pokok + Margin)</p>
                    <p class="text-xl font-bold text-gray-900">
                        Rp {{ number_format($result['total_payment'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Info Note -->
            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                <p class="text-xs text-amber-700 flex items-start">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>
                        Hasil simulasi ini bersifat estimasi. Angsuran sebenarnya dapat berbeda tergantung pada hasil analisis dan persetujuan pembiayaan.
                    </span>
                </p>
            </div>
        </div>
    @endif
</div>
