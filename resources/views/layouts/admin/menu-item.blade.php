@php
    $isActive = request()->routeIs($menu->route . '*') || request()->routeIs(str_replace('.index', '.*', $menu->route));
    $icon = $icons[$menu->key] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
    $badgeCount = $badgeCounts[$menu->key] ?? 0;
@endphp

<a @click="closeSidebarOnMobile()" href="{{ route($menu->route) }}"
   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200 {{ $isActive ? 'bg-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
    <div class="relative flex items-center justify-center w-8 h-8 rounded-lg flex-shrink-0 {{ $isActive ? 'bg-emerald-500' : 'bg-white/10 group-hover:bg-white/20' }} transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
        @if($badgeCount > 0)
            <span class="absolute -top-1.5 -right-1.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white {{ $menu->key === 'complaints' ? 'bg-red-500' : 'bg-amber-500' }} rounded-full ring-2 ring-[#1e3a5f]">
                {{ $badgeCount > 9 ? '9+' : $badgeCount }}
            </span>
        @endif
    </div>
    <span class="text-sm font-medium whitespace-nowrap">{{ $menu->name }}</span>
</a>
