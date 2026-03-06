<x-frontend-layout>
    <x-slot name="title">Simulasi Pembiayaan - {{ $companyInfo->name ?? 'BPRS Bangka Belitung' }}</x-slot>

    <!-- Hero Section -->
    <section class="relative pt-24 sm:pt-28 md:pt-32 pb-16 sm:pb-20 md:pb-24 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4 sm:mb-6 tracking-tight">Simulasi Pembiayaan</h1>
            <p class="text-base sm:text-lg md:text-xl text-emerald-50 max-w-2xl mx-auto px-4">
                Hitung estimasi angsuran pembiayaan Anda dengan mudah dan cepat.
                Rencanakan keuangan Anda sebelum mengajukan pembiayaan.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Calculator -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-lg shadow-gray-200/50 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                        <livewire:frontend.financing-simulation.calculator />
                    </div>
                </div>

                <!-- Info Sidebar -->
                <div class="space-y-4 sm:space-y-6">
                    <!-- How It Works -->
                    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg shadow-gray-200/50 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <h3 class="font-bold text-gray-900 mb-3 sm:mb-4 flex items-center text-sm sm:text-base">
                            <span class="w-7 h-7 sm:w-8 sm:h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            Cara Menggunakan
                        </h3>
                        <ol class="space-y-2 sm:space-y-3 text-xs sm:text-sm text-gray-600">
                            <li class="flex items-start">
                                <span class="w-5 h-5 sm:w-6 sm:h-6 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-xs font-bold mr-2 sm:mr-3 flex-shrink-0">1</span>
                                <span>Pilih jenis pembiayaan yang Anda inginkan</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-5 h-5 sm:w-6 sm:h-6 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-xs font-bold mr-2 sm:mr-3 flex-shrink-0">2</span>
                                <span>Masukkan jumlah pembiayaan yang dibutuhkan</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-5 h-5 sm:w-6 sm:h-6 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-xs font-bold mr-2 sm:mr-3 flex-shrink-0">3</span>
                                <span>Pilih jangka waktu pembiayaan</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-5 h-5 sm:w-6 sm:h-6 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-xs font-bold mr-2 sm:mr-3 flex-shrink-0">4</span>
                                <span>Klik "Hitung Simulasi" untuk melihat hasil</span>
                            </li>
                        </ol>
                    </div>

                    <!-- Benefits -->
                    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg shadow-gray-200/50 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <h3 class="font-bold text-gray-900 mb-3 sm:mb-4 flex items-center text-sm sm:text-base">
                            <span class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 text-blue-600 flex-shrink-0">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            Keunggulan Pembiayaan Kami
                        </h3>
                        <ul class="space-y-2 sm:space-y-3 text-xs sm:text-sm text-gray-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Proses cepat dan mudah
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Margin kompetitif
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Sesuai prinsip syariah
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Angsuran tetap
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Tenor fleksibel
                            </li>
                        </ul>
                    </div>

                    <!-- Contact CTA -->
                    <div class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-lg shadow-emerald-600/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <h3 class="font-bold mb-2 text-base sm:text-lg">Tertarik dengan Pembiayaan?</h3>
                        <p class="text-emerald-50 text-xs sm:text-sm mb-4 sm:mb-6 leading-relaxed">
                            Hubungi kami untuk informasi lebih lanjut atau kunjungi kantor terdekat.
                        </p>
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center w-full px-4 py-3 bg-white text-emerald-700 rounded-xl font-semibold text-xs sm:text-sm hover:bg-emerald-50 transition-all duration-300 shadow-sm touch-manipulation active:scale-95 min-h-[48px]">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-frontend-layout>
