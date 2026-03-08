@props([
    'id',
    'name',
    'label' => '',
    'type' => 'text',
    'icon' => null,
    'placeholder' => '',
    'required' => false
])

<div class="space-y-2">
    @if($label)
        <label for="{{ $id }}" class="text-sm font-medium text-slate-700 dark:text-slate-300 ml-1">
            {{ $label }}
        </label>
    @endif
    
    <div class="relative">
        @if($icon)
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">
                {{ $icon }}
            </span>
        @endif
        
        <input 
            id="{{ $id }}" 
            name="{{ $name }}" 
            type="{{ $type }}" 
            placeholder="{{ $placeholder }}" 
            @if($required) required @endif
            {{ $attributes->merge(['class' => 'w-full ' . ($icon ? 'pl-12' : 'px-4') . ' pr-4 py-4 bg-slate-50 dark:bg-primary/5 border border-slate-200 dark:border-primary/20 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600']) }}
        >
    </div>
    
    @error($name)
        <p class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</p>
    @enderror
</div>
