<div class="flex items-center gap-3">
    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-lg size-12 border border-slate-200 dark:border-primary/20 shrink-0 overflow-hidden"
        style='background-image: url("{{ $item->image_url ?? 'https://via.placeholder.com/150' }}");'>
    </div>
    <div class="flex flex-col min-w-0">
        <span class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $item->name }}</span>
        <span class="text-xs text-slate-500 dark:text-slate-400">SKU: {{ $item->sku ?? 'N/A' }}</span>
    </div>
</div>