<div class="flex flex-col gap-2 w-full md:w-64">
    <div class="flex justify-between items-end">
        @php
            $percentage = $item->stock_percentage;
            $colorClass = match (true) {
                $percentage <= 25 => 'text-rose-600',
                $percentage <= 60 => 'text-amber-600',
                default => 'text-emerald-600',
            };
            $barClass = match (true) {
                $percentage <= 25 => 'bg-rose-600 animate-pulse',
                $percentage <= 60 => 'bg-amber-500',
                default => 'bg-emerald-500',
            };
        @endphp
        <p class="text-sm font-bold {{ $colorClass }} flex items-center gap-1">
            {{ $item->stock }} de 20 unidades
        </p>
        <p class="text-xs text-slate-400">{{ $percentage }}%</p>
    </div>
    <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
        <div class="h-full {{ $barClass }} rounded-full" style="width: {{ $percentage }}%;"></div>
    </div>
</div>