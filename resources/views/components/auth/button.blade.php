@props([
    'type' => 'submit',
    'iconEnd' => null,
])

<button 
    type="{{ $type }}"
    wire:loading.attr="disabled"
    {{ $attributes->merge(['class' => 'w-full bg-primary hover:bg-primary/90 text-white font-semibold py-4 rounded-lg shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden']) }}
>
    <!-- Normal State -->
    <div wire:loading.remove class="flex items-center gap-2">
        <span>{{ $slot }}</span>
        @if($iconEnd)
            <span class="material-symbols-outlined text-xl transition-transform group-hover:translate-x-1">
                {{ $iconEnd }}
            </span>
        @endif
    </div>

    <!-- Loading State -->
    <div wire:loading class="flex items-center gap-2">
        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>{{ __('Carregando...') }}</span>
    </div>
</button>
