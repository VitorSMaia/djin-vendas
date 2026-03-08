@php
    $value = data_get($item, $field); // 'Crítico', 'Alerta', etc.
    $status = $value;

    // Mapeamento de cores baseado nos termos do Model Product
    $config = [
        'Crítico' => [
            'text' => 'text-red-600 dark:text-red-400',
            'dot' => 'bg-red-500',
            'label' => 'Sem Estoque / Crítico'
        ],
        'Alerta' => [
            'text' => 'text-orange-600 dark:text-orange-400',
            'dot' => 'bg-orange-500',
            'label' => 'Estoque Baixo'
        ],
        'Médio' => [
            'text' => 'text-amber-600 dark:text-amber-400',
            'dot' => 'bg-amber-500',
            'label' => 'Estoque Médio'
        ],
        'Estável' => [
            'text' => 'text-emerald-600 dark:text-emerald-400',
            'dot' => 'bg-emerald-500',
            'label' => 'Em Estoque'
        ],
    ];

    $style = $config[$status] ?? [
        'text' => 'text-slate-600 dark:text-slate-400',
        'dot' => 'bg-slate-400',
        'label' => $value
    ];
@endphp

<div class="flex items-center gap-1.5 {{ $style['text'] }} font-bold text-xs uppercase tracking-wider">
    <span class="size-1.5 rounded-full {{ $style['dot'] }}"></span>
    {{ $style['label'] }}
</div>