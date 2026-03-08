@php
    $value = data_get($item, $field);
    $status = strtolower($value);

    $colors = [
        // Eventos de Auditoria
        'created' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
        'updated' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
        'deleted' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',

        // Status Gerais
        'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
        'inactive' => 'bg-slate-100 text-slate-700 dark:bg-slate-500/10 dark:text-slate-400',
        'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
        'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
        'failed' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
    ];

    $colorClass = $colors[$status] ?? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';

    // Mapeamento de labels formatados
    $labels = [
        'created' => 'Criado',
        'updated' => 'Atualizado',
        'deleted' => 'Excluído',
        'active' => 'Ativo',
        'inactive' => 'Inativo',
        'pending' => 'Pendente',
        'completed' => 'Concluído',
        'failed' => 'Falha',
    ];

    $label = $labels[$status] ?? ucfirst($value);
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
    {{ $label }}
</span>