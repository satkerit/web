@props(['title' => null, 'subtitle' => null, 'actions' => null, 'noPadding' => false])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 overflow-hidden transition-all duration-200 hover:shadow-md']) }}>
    @if($title)
        <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight">{{ $title }}</h3>
                @if($subtitle)
                    <p class="mt-0.5 text-sm text-slate-500 font-medium">{{ $subtitle }}</p>
                @endif
            </div>
            @if($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    <div class="{{ $noPadding ? '' : 'p-5 sm:p-6' }}">
        {{ $slot }}
    </div>
</div>
