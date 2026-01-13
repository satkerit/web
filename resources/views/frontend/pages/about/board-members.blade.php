<x-frontend-layout>
    <x-slot name="title">{{ $title }} - BPRS Bangka Belitung</x-slot>

    <!-- Hero -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f766e 0%, #3bdacb 50%, #0d9488 100%);">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">{{ $title }}</h1>
            <p class="text-lg text-white/80">{{ $subtitle }}</p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($members->count() > 0)
            <div class="flex flex-wrap justify-center gap-8">
                @foreach($members as $member)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition w-full sm:w-80">
                    @if($member->photo)
                    <img src="{{ Storage::url($member->photo) }}" alt="{{ $member->name }}" class="w-full h-64 object-cover object-top">
                    @else
                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    @endif
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $member->name }}</h3>
                        <p class="text-primary-600 font-medium mb-3">{{ $member->position }}</p>

                        @if($member->biography)
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($member->biography, 150) }}</p>
                        @endif

                        <button
                            x-data
                            @click="$dispatch('open-modal', { member: {{ json_encode($member) }} })"
                            class="text-primary-600 hover:text-primary-700 text-sm font-medium"
                        >
                            Lihat Profil Lengkap →
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 text-gray-500">
                Belum ada data tersedia
            </div>
            @endif
        </div>
    </section>

    <!-- Modal -->
    <div
        x-data="{ open: false, member: null }"
        @open-modal.window="open = true; member = $event.detail.member"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="open = false" class="fixed inset-0 bg-black bg-opacity-50"></div>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="p-6" x-show="member">
                    <div class="flex flex-col md:flex-row gap-6">
                        <template x-if="member && member.photo">
                            <img :src="'/storage/' + member.photo" :alt="member.name" class="w-48 h-48 object-cover rounded-lg flex-shrink-0">
                        </template>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900" x-text="member?.name"></h3>
                            <p class="text-primary-600 font-medium mb-4" x-text="member?.position"></p>
                            <template x-if="member && member.biography">
                                <p class="text-gray-600 text-sm" x-text="member.biography"></p>
                            </template>
                        </div>
                    </div>

                    <template x-if="member && member.education && member.education.length > 0">
                        <div class="mt-6">
                            <h4 class="font-semibold text-gray-900 mb-2">Pendidikan</h4>
                            <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                                <template x-for="edu in member.education" :key="edu">
                                    <li x-text="edu"></li>
                                </template>
                            </ul>
                        </div>
                    </template>

                    <template x-if="member && member.experience && member.experience.length > 0">
                        <div class="mt-6">
                            <h4 class="font-semibold text-gray-900 mb-2">Pengalaman</h4>
                            <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                                <template x-for="exp in member.experience" :key="exp">
                                    <li x-text="exp"></li>
                                </template>
                            </ul>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>
