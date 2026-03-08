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

    {{-- Table Content --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-primary/5 border-b border-slate-200 dark:border-primary/20">
                    @foreach($columns as $column)
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 dark:text-slate-400">
                            <button wire:click="sortBy('{{ $column['field'] }}')"
                                class="flex items-center gap-1 hover:text-primary transition-colors">
                                {{ $column['label'] }}
                                @if($sortField === $column['field'])
                                    <span
                                        class="material-symbols-outlined text-xs">{{ $sortDirection === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                @endif
                            </button>
                        </th>
                    @endforeach
                    <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 dark:text-slate-400 text-right">
                        Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-primary/10">
                @forelse($items as $item)
                    <tr wire:key="row-{{ $item->id }}"
                        class="hover:bg-slate-50 dark:hover:bg-primary/5 transition-colors group">
                        @foreach($columns as $column)
                            <td class="px-6 py-4">
                                @if(isset($column['view']) && view()->exists($column['view']))
                                    @include($column['view'], ['item' => $item, 'field' => $column['field']])
                                @else
                                    <span class="text-sm text-slate-900 dark:text-slate-100">
                                        {{ data_get($item, $column['field']) }}
                                    </span>
                                @endif
                            </td>
                        @endforeach

                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button wire:click="$dispatch('{{ $editEvent ?? 'editProduct' }}', { id: {{ $item->id }} })"
                                    class="p-2 rounded-lg hover:bg-primary/10 text-slate-400 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                <button
                                    wire:click="$dispatch('{{ $deleteEvent ?? 'deleteProduct' }}', { id: {{ $item->id }} })"
                                    class="p-2 rounded-lg hover:bg-rose-500/10 text-slate-400 hover:text-rose-500 transition-colors">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                                <button wire:click="$dispatch('quickAdjust', { id: {{ $item->id }} })"
                                    class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors ml-2">
                                    <span class="material-symbols-outlined text-xs">tune</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 1 }}" class="px-6 py-12 text-center text-slate-500">
                            Nenhum item encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($items->hasPages())
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800">
            {{ $items->links() }}
        </div>
    @endif
</div>