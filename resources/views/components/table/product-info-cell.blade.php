<div class="flex items-center gap-4 flex-1 w-full">
    <div
        class="size-16 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0 overflow-hidden border border-slate-200 dark:border-slate-700">
        @if($item->image_url)
            <img class="object-cover size-full" src="{{ $item->image_url }}" alt="{{ $item->name }}" />
        @else
            <span class="material-symbols-outlined text-slate-400">image</span>
        @endif
    </div>
    <div class="flex flex-col gap-1 flex-1">
        <h4 class="font-bold text-slate-900 dark:text-slate-100">{{ $item->name }}</h4>
        <div class="flex items-center gap-2">
            @php
                $status = $item->stock_status;
                $statusClass = match ($status) {
                    'Crítico' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400',
                    'Alerta' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400',
                    'Médio' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400',
                    default => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400',
                };
            @endphp
            <span
                class="px-2 py-0.5 rounded {{ $statusClass }} text-[10px] font-bold uppercase tracking-wider flex items-center gap-1">
                @if(in_array($status, ['Crítico', 'Alerta']))
                    <span class="material-symbols-outlined text-xs">error</span>
                @endif
                {{ $status }}
            </span>
            <span class="text-xs text-slate-500 dark:text-slate-400">SKU: {{ $item->sku ?? 'N/A' }}</span>
        </div>
    </div>
</div>