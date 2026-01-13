<x-frontend-layout>
    <x-slot name="title">Download Logo - {{ $company->name ?? 'BPRS Bangka Belitung' }}</x-slot>

    <!-- Hero -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Download Logo</h1>
            <p class="text-lg text-white/80">Unduh logo resmi {{ $company->name ?? 'BPRS Bangka Belitung' }}</p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                @if($logoAvailable)
                    <!-- Logo Preview -->
                    <div class="text-center mb-8">
                        <div class="w-64 h-64 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl mx-auto flex items-center justify-center mb-6 p-8 border border-gray-200">
                            <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name ?? 'Logo' }}" class="max-w-full max-h-full object-contain">
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Logo {{ $company->name ?? 'BPRS Bangka Belitung' }}</h2>
                        <p class="text-gray-500 mt-2">Format tersedia: {{ strtoupper($logoExtension) }}</p>
                    </div>

                    <!-- Download Buttons -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                        {{-- Download format asli --}}
                        <a href="{{ route('download-logo.download', $logoExtension) }}" 
                           class="group flex items-center justify-center px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-emerald-500/30">
                            <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download {{ strtoupper($logoExtension) }}
                            <span class="ml-2 text-xs bg-white/20 px-2 py-0.5 rounded-full">Original</span>
                        </a>

                        {{-- Download PNG (jika bukan PNG) --}}
                        @if($logoExtension !== 'png' && in_array($logoExtension, ['jpg', 'jpeg', 'webp']))
                        <a href="{{ route('download-logo.download', 'png') }}" 
                           class="group flex items-center justify-center px-6 py-4 border-2 border-emerald-600 text-emerald-600 rounded-xl hover:bg-emerald-50 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download PNG
                        </a>
                        @endif

                        {{-- Download JPG (jika bukan JPG) --}}
                        @if(!in_array($logoExtension, ['jpg', 'jpeg']) && in_array($logoExtension, ['png', 'webp']))
                        <a href="{{ route('download-logo.download', 'jpg') }}" 
                           class="group flex items-center justify-center px-6 py-4 border-2 border-emerald-600 text-emerald-600 rounded-xl hover:bg-emerald-50 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download JPG
                        </a>
                        @endif

                        {{-- Download WebP (jika bukan WebP) --}}
                        @if($logoExtension !== 'webp' && in_array($logoExtension, ['png', 'jpg', 'jpeg']))
                        <a href="{{ route('download-logo.download', 'webp') }}" 
                           class="group flex items-center justify-center px-6 py-4 border-2 border-emerald-600 text-emerald-600 rounded-xl hover:bg-emerald-50 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download WebP
                        </a>
                        @endif
                    </div>

                @else
                    <!-- No Logo Available -->
                    <div class="text-center py-12">
                        <div class="w-32 h-32 bg-gray-100 rounded-2xl mx-auto flex items-center justify-center mb-6">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Logo Belum Tersedia</h2>
                        <p class="text-gray-500">Logo perusahaan belum diupload. Silakan hubungi administrator.</p>
                    </div>
                @endif

                <!-- Usage Guidelines -->
                <div class="mt-8 p-6 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-200">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">Panduan Penggunaan Logo</h3>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Logo hanya boleh digunakan untuk keperluan yang berkaitan dengan {{ $company->name ?? 'BPRS Bangka Belitung' }}
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Tidak diperkenankan mengubah warna, proporsi, atau elemen logo
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Pastikan logo memiliki ruang kosong yang cukup di sekelilingnya
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Untuk penggunaan komersial, harap hubungi tim marketing kami
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact for More -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">
                        Butuh format lain atau resolusi lebih tinggi? 
                        <a href="{{ route('contact') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Hubungi kami</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-frontend-layout>
