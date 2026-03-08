@props([
    'label',
    'value',
    'icon',
    'trend' => null,
    'trendIcon' => 'trending_up',
    'trendColor' => 'text-emerald-500',
    'iconColor' => 'text-primary'
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-primary/20 bg-white dark:bg-primary/5 shadow-sm']) }}>
    <div class="flex items-center justify-between">
        <p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">{{ $label }}</p>
        <span class="material-symbols-outlined {{ $iconColor }}">{{ $icon }}</span>
    </div>
    <p class="text-slate-900 dark:text-slate-100 text-3xl font-bold">{{ $value }}</p>
    
    @if($trend)
        <p class="{{ $trendColor }} text-sm font-medium flex items-center gap-1">
            <span class="material-symbols-outlined text-xs">{{ $trendIcon }}</span> {{ $trend }}
        </p>
    @endif
</div>
