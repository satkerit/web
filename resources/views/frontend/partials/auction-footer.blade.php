<!-- Auction Footer -->
<footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-32 h-32 bg-orange-500/10 rounded-full blur-xl float-auction"></div>
    <div class="absolute bottom-10 right-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-xl float-auction" style="animation-delay: 2s;"></div>

    <div class="relative">
        <!-- Main Footer Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        @if($company?->logo)
                            <img src="{{ \App\Helpers\StorageHelper::url($company->logo) }}" 
                                 alt="{{ $company->name }}" 
                                 class="h-12 w-auto">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-xl">{{ substr($company->name ?? 'BPRS', 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-white tracking-tight">Lelang Agunan</h3>
                            <p class="text-orange-300 font-medium">{{ $company->name ?? 'BPRS Bangka Belitung' }}</p>
                        </div>
                    </div>
                    
                    <p class="text-gray-300 mb-6 leading-relaxed max-w-md">
                        Platform lelang agunan terpercaya untuk mendapatkan properti dan aset berkualitas dengan harga terbaik. 
                        Proses transparan, aman, dan sesuai regulasi.
                    </p>

                    <!-- Contact Info -->
                    <div class="space-y-3">
                        @if($company->phone)
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Telepon</p>
                                <a href="tel:{{ $company->phone }}" class="text-white hover:text-orange-300 transition-colors font-medium">
                                    {{ $company->phone }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($company->email)
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Email</p>
                                <a href="mailto:{{ $company->email }}" class="text-white hover:text-orange-300 transition-colors font-medium">
                                    {{ $company->email }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($company->address)
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center mt-1">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Alamat</p>
                                <p class="text-white leading-relaxed">{{ $company->address }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-6 tracking-tight">Navigasi Cepat</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('auctions.index') }}" class="text-gray-300 hover:text-orange-300 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>Semua Lelang</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('auctions.index', ['asset_type' => 'rumah']) }}" class="text-gray-300 hover:text-orange-300 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>Lelang Rumah</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('auctions.index', ['asset_type' => 'tanah']) }}" class="text-gray-300 hover:text-orange-300 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>Lelang Tanah</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('auctions.index', ['asset_type' => 'ruko']) }}" class="text-gray-300 hover:text-orange-300 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>Lelang Ruko</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('auctions.index', ['asset_type' => 'kendaraan']) }}" class="text-gray-300 hover:text-orange-300 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>Lelang Kendaraan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-300 hover:text-orange-300 transition-colors flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span>Kembali ke Beranda</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Auction Info -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-6">Informasi Lelang</h4>
                    <div class="space-y-4">
                        <!-- Live Auction Count -->
                        <div class="bg-gradient-to-r from-orange-500/20 to-red-500/20 rounded-xl p-4 border border-orange-500/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-orange-300 text-sm font-medium">Lelang Aktif</p>
                                    <p class="text-2xl font-bold text-white">
                                        {{ \App\Models\Auction::where('status', 'registration_open')->count() }}
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-orange-500/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Operating Hours -->
                        <div class="bg-gray-800/50 rounded-xl p-4 border border-gray-700">
                            <h5 class="text-white font-semibold mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Jam Operasional
                            </h5>
                            <div class="text-sm text-gray-300 space-y-1">
                                <div class="flex justify-between">
                                    <span>Senin - Jumat</span>
                                    <span>08:00 - 16:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Sabtu</span>
                                    <span>08:00 - 12:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Minggu</span>
                                    <span class="text-red-400">Tutup</span>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="bg-red-500/20 rounded-xl p-4 border border-red-500/30">
                            <h5 class="text-white font-semibold mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                Kontak Darurat
                            </h5>
                            <p class="text-sm text-gray-300">
                                Untuk bantuan mendesak terkait lelang
                            </p>
                            @if($company->phone)
                            <a href="tel:{{ $company->phone }}" class="text-red-300 hover:text-red-200 font-semibold text-sm">
                                {{ $company->phone }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-center md:text-left">
                        <p class="text-gray-400 text-sm">
                            © {{ date('Y') }} {{ $company->name ?? 'BPRS Bangka Belitung' }}. Semua hak dilindungi.
                        </p>
                        <p class="text-gray-500 text-xs mt-1">
                            Platform Lelang Agunan Resmi dan Terpercaya
                        </p>
                    </div>

                    <!-- Social Media & Links -->
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-4">
                            @if($company->website)
                            <a href="{{ $company->website }}" target="_blank" 
                               class="text-gray-400 hover:text-orange-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                </svg>
                            </a>
                            @endif
                            
                            @if($company->email)
                            <a href="mailto:{{ $company->email }}" 
                               class="text-gray-400 hover:text-orange-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </a>
                            @endif
                        </div>

                        <div class="text-xs text-gray-500 flex items-center space-x-2">
                            <span>Powered by</span>
                            <span class="text-orange-400 font-semibold">BPRS Technology</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>