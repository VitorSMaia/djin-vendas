<div class="flex flex-col">
    {{-- Search and Filters --}}
    @if(!empty($searchableColumns) || !empty($statusFilterOptions))
        <div
            class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="relative w-full sm:max-w-xs">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <span class="material-symbols-outlined text-sm">search</span>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg leading-5 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-primary sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Buscar...">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                @if(!empty($statusFilterOptions))
                    <select wire:model.live="statusFilter"
                        class="flex-1 sm:flex-none px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm font-medium focus:ring-primary">
                        <option value="">Todos os Status</option>
                        @foreach($statusFilterOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                @endif
                <button
                    class="flex-1 sm:flex-none px-4 py-2 bg-slate-100 dark:bg-slate-800 rounded-lg text-sm font-medium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">download</span> Exportar
                </button>
            </div>
        </div>
    @endif

    {{-- Table / List Content --}}
    <div class="divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($items as $item)
            <div wire:key="row-{{ $item->id }}">
                @php
                    // Check if there's a custom row view, otherwise render columns
                    $rowView = $rowView ?? null;
                @endphp

                @if($rowView)
                    @include($rowView, ['item' => $item])
                @else
                    <div class="p-4 md:p-6 flex flex-col md:flex-row items-start md:items-center gap-6">
                        @foreach($columns as $column)
                            <div class="flex-1">
                                @if(isset($column['view']) && view()->exists($column['view']))
                                    @include($column['view'], ['item' => $item, 'field' => $column['field']])
                                @else
                                    <span class="text-sm text-slate-900 dark:text-slate-100">
                                        {{ data_get($item, $column['field']) }}
                                    </span>
                                @endif
                            </div>
                        @endforeach

                        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                            <button wire:click="$dispatch('editProduct', { productId: {{ $item->id }} })"
                                class="p-2 text-slate-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button wire:click="$dispatch('deleteProduct', { id: {{ $item->id }} })"
                                class="p-2 text-slate-400 hover:text-rose-500 transition-colors">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                            <button wire:click="$dispatch('quickAdjust', { id: {{ $item->id }} })"
                                class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors ml-4">
                                <span class="material-symbols-outlined text-sm">add</span> Ajuste Rápido
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="p-8 text-center text-slate-500">
                Nenhum item encontrado.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($items->hasPages())
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800">
            {{ $items->links() }}
        </div>
    @endif
</div>