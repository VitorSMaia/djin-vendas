@php
    $value = data_get($item, $field);
    $role = strtolower($value);

    $colors = [
        'admin' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400',
        'manager' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
        'user' => 'bg-slate-100 text-slate-700 dark:bg-slate-500/10 dark:text-slate-400',
    ];

    $colorClass = $colors[$role] ?? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
@endphp

<span
    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wider {{ $colorClass }}">
    {{ $value }}
</span>