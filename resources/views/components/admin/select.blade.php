@props([
    'label' => null,
    'name' => null,
    'model' => null,
    'options' => [],
    'error' => null,
    'helper' => null,
    'required' => false,
    'placeholder' => 'Pilih opsi'
])

<div class="group">
    @if($label)
        <label for="{{ $name ?? $model }}" class="block text-sm font-semibold text-slate-700 mb-1.5 ml-0.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <select
            name="{{ $name }}"
            wire:model="{{ $model }}"
            id="{{ $name ?? $model }}"
            {{ $attributes->merge([
                'class' => 'block w-full rounded-xl border-0 py-2.5 px-4 text-slate-900 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6 transition-all duration-200 ease-in-out ' . ($error ? 'ring-red-300 focus:ring-red-500 bg-red-50/50' : 'hover:ring-slate-300')
            ]) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach($options as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
        @if($error)
            <div class="absolute top-2 right-2">
                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>

    @if($helper && !$error)
        <p class="mt-1.5 text-xs text-slate-500 ml-0.5">{{ $helper }}</p>
    @endif

    @if($error)
        <p class="mt-1.5 text-xs text-red-600 font-medium ml-0.5 animate-pulse">{{ $error }}</p>
    @endif
</div>
