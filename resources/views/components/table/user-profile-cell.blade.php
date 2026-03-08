@php
    $userName = data_get($item, $field) ?? 'N/A';
@endphp

<div class="flex items-center gap-3">
    <div
        class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs uppercase">
        {{ substr($userName, 0, 1) }}
    </div>
    <span class="text-sm font-medium text-slate-900 dark:text-slate-100">
        {{ $userName }}
    </span>
</div>