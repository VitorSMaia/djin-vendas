<div class="px-6 py-8 max-w-[1440px] mx-auto w-full space-y-8">
    <!-- Page Heading -->
    <div class="flex flex-wrap justify-between items-end gap-3 mb-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-slate-900 dark:text-slate-100 text-4xl font-black leading-tight tracking-tight">Estoque e Produtos</h1>
            <p class="text-slate-500 dark:text-slate-400 text-base font-normal">Acompanhe níveis de estoque, preços e catálogo em tempo real.</p>
        </div>
        <div>
            <x-primary-button class="gap-2">
                <span class="material-symbols-outlined text-sm">add</span>
                Novo Produto
            </x-primary-button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stats-card 
            label="Total de Produtos" 
            :value="$stats['total']" 
            icon="inventory_2" 
            trend="+12% este mês"
        />
        <x-stats-card 
            label="Em Estoque" 
            :value="$stats['in_stock']" 
            icon="check_circle" 
            icon-color="text-emerald-500"
            trend="82.2% do catálogo"
            trend-color="text-slate-500"
            trend-icon="info"
        />
        <x-stats-card 
            label="Estoque Baixo" 
            :value="$stats['low_stock']" 
            icon="warning" 
            icon-color="text-orange-500"
            trend="Requer atenção"
            trend-color="text-orange-500"
            trend-icon="notification_important"
        />
        <x-stats-card 
            label="Sem Estoque" 
            :value="$stats['out_of_stock']" 
            icon="error" 
            icon-color="text-red-500"
            trend="+1 desde ontem"
            trend-color="text-red-500"
            trend-icon="arrow_upward"
        />
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden">
        @livewire('dynamic-table', [
            'model' => \App\Models\Product::class,
            'columns' => [
                [
                    'label' => 'Produto',
                    'field' => 'name',
                    'sortable' => true,
                    'view' => 'components.table.product-info-cell',
                ],
                [
                    'label' => 'Preço Venda',
                    'field' => 'price',
                    'sortable' => true,
                ],
                [
                    'label' => 'Preço Custo',
                    'field' => 'cost_price',
                    'sortable' => true,
                ],
                [
                    'label' => 'Estoque',
                    'field' => 'stock',
                    'sortable' => true,
                ],
                [
                    'label' => 'Status',
                    'field' => 'stock_status',
                    'sortable' => false,
                    'view' => 'components.table.status-badge',
                ],
            ],
            'searchableColumns' => ['name', 'sku'],
            'statusFilterColumn' => 'status',
            'statusFilterOptions' => [
                'active' => 'Ativo',
                'inactive' => 'Inativo',
            ],
            'perPage' => 10,
        ])
    </div>

    <!-- Modal de Ajuste de Estoque -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-sm w-full overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">
                        @if($actionType === 'IN') 🟢 Entrada
                        @elseif($actionType === 'OUT_SALE') 🔘 Saída Manual
                        @else 🔴 Registrar Avaria @endif
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-500">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <p class="font-bold text-xl text-slate-900 dark:text-white leading-tight">{{ $selectedProduct->name }}</p>
                        <p class="text-sm text-slate-500 mt-1">Estoque atual: <span class="font-bold">{{ $selectedProduct->stock }}</span></p>
                    </div>

                    <div x-data="{ qty: @entangle('quantity') }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Quantidade</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" @click="if(qty > 1) qty--"
                                class="flex items-center justify-center h-14 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 transition-colors">
                                <span class="material-symbols-outlined">remove</span>
                            </button>
                            <input type="number" wire:model="quantity" 
                                class="w-full text-center text-2xl font-black bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-primary focus:border-primary h-14">
                            <button type="button" @click="qty++"
                                class="flex items-center justify-center h-14 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 transition-colors">
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </div>
                        @error('quantity') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">
                            {{ $actionType === 'OUT_LOSS' ? 'Motivo da Avaria' : 'Observação' }}
                        </label>
                        <textarea wire:model="reason" 
                                  placeholder="{{ $actionType === 'OUT_LOSS' ? 'Ex: Caiu no chão, Embalagem rompida...' : 'Opcional...' }}"
                                  class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 focus:ring-primary focus:border-primary min-h-[100px]"></textarea>
                        @error('reason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700 flex gap-3">
                    <x-secondary-button wire:click="closeModal" class="flex-1 justify-center py-4">Cancelar</x-secondary-button>
                    <x-primary-button wire:click="submitMovement" class="flex-1 justify-center py-4 {{ $actionType === 'OUT_LOSS' ? 'bg-red-600 hover:bg-red-700' : '' }}">
                        Confirmar
                    </x-primary-button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Edição de Produto -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-lg w-full overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">edit_square</span>
                        Editar Produto
                    </h3>
                    <button wire:click="closeEditModal" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto no-scrollbar">
                    <!-- Name & SKU -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nome do Produto</label>
                            <input type="text" wire:model="editName" 
                                class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-primary focus:border-primary h-12 px-4 shadow-sm">
                            @error('editName') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Código SKU</label>
                            <input type="text" wire:model="editSku" 
                                class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-primary focus:border-primary h-12 px-4 shadow-sm uppercase">
                            @error('editSku') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Prices -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Preço de Custo</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">R$</span>
                                <input type="number" step="0.01" wire:model="editCostPrice" 
                                    class="w-full pl-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-primary focus:border-primary h-12 shadow-sm font-semibold">
                            </div>
                            @error('editCostPrice') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Preço de Venda</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">R$</span>
                                <input type="number" step="0.01" wire:model="editPrice" 
                                    class="w-full pl-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-primary focus:border-primary h-12 shadow-sm font-bold text-primary">
                            </div>
                            @error('editPrice') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Status do Produto</label>
                        <select wire:model="editStatus" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-primary focus:border-primary h-12 px-4 shadow-sm font-medium">
                            <option value="active">Ativo (Visível no PDV)</option>
                            <option value="inactive">Inativo (Oculto)</option>
                        </select>
                        @error('editStatus') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700 flex gap-3">
                    <button wire:click="closeEditModal" class="flex-1 justify-center py-3 px-4 font-bold rounded-xl text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="updateProduct" class="flex-1 justify-center py-3 px-4 font-bold rounded-xl text-white bg-primary hover:bg-primary/90 transition-colors shadow-sm shadow-primary/30 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        Salvar Alterações
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>