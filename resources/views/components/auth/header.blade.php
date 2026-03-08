@props([
    'logoIcon' => 'auto_awesome',
    'title' => '',
    'subtitle' => ''
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center mb-10']) }}>
    <div class="w-16 h-16 bg-primary rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-primary/30">
        <span class="material-symbols-outlined text-white text-4xl">
            {{ $logoIcon }}
        </span>
    </div>
    <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
        {{ $title }}
    </h1>
    @if($subtitle)
        <p class="text-slate-500 dark:text-primary/60 text-sm mt-2">
            {{ $subtitle }}
        </p>
    @endif
</div>
