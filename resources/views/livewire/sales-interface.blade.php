<div class="relative flex h-auto min-screen w-full flex-col group/design-root overflow-x-hidden pb-24">
    {{-- Header --}}
    <header class="flex items-center justify-between whitespace-nowrap border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-6 py-3 sticky top-0 z-50">
        <div class="flex items-center gap-4 text-slate-900 dark:text-slate-100">
            <div class="size-6 text-primary">
                <span class="material-symbols-outlined text-3xl">inventory_2</span>
            </div>
            <div>
                <h2 class="text-slate-900 dark:text-slate-100 text-lg font-bold leading-tight tracking-tight">Fluxo de Venda</h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs">Sem Atrito</p>
            </div>
        </div>
        <div class="flex flex-1 justify-end gap-4">
            <div class="flex gap-2">
                <button class="flex items-center justify-center rounded-lg h-10 w-10 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <button wire:click="openModal" class="flex items-center justify-center rounded-lg h-10 w-10 bg-primary text-white hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined">add</span>
                </button>
            </div>
        </div>
    </header>

    <main class="flex flex-col max-w-[1200px] mx-auto w-full p-4 md:p-8 gap-8">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Items --}}
            <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total de Itens</p>
                    <span class="material-symbols-outlined text-primary">category</span>
                </div>
                <p class="text-slate-900 dark:text-slate-100 text-3xl font-bold">{{ number_format($summary['total_items'], 0, ',', '.') }}</p>
                <p class="text-emerald-600 text-xs font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_up</span> +2.4% este mês
                </p>
            </div>

            {{-- Low Stock --}}
            <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Baixo Estoque</p>
                    <span class="material-symbols-outlined text-rose-500">warning</span>
                </div>
                <p class="text-slate-900 dark:text-slate-100 text-3xl font-bold">{{ str_pad($summary['low_stock'], 2, '0', STR_PAD_LEFT) }}</p>
                <p class="text-rose-500 text-xs font-semibold">Requer atenção imediata</p>
            </div>

            {{-- Stock Value --}}
            <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Valor em Estoque</p>
                    <span class="material-symbols-outlined text-emerald-500">payments</span>
                </div>
                <p class="text-slate-900 dark:text-slate-100 text-3xl font-bold">R$ {{ number_format($summary['total_value'] / 1000, 1, ',', '.') }}k</p>
                <p class="text-slate-500 text-xs">Patrimônio imobilizado</p>
            </div>

            {{-- Rotation --}}
            <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Giro de Estoque</p>
                    <span class="material-symbols-outlined text-amber-500">sync_alt</span>
                </div>
                <p class="text-slate-900 dark:text-slate-100 text-3xl font-bold">{{ $summary['rotation'] }}</p>
                <p class="text-emerald-600 text-xs font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_up</span> Ótima performance
                </p>
            </div>
        </div>

        {{-- Inventory Table --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Gestão de Inventário</h3>
            </div>
            
            @livewire('dynamic-table', [
                'model' => \App\Models\Product::class,
                'columns' => [
                    [
                        'field' => 'name',
                        'view' => 'components.table.product-info-cell',
                    ],
                    [
                        'field' => 'stock',
                        'view' => 'components.table.stock-level-cell',
                    ],
                ],
                'searchableColumns' => ['name', 'sku'],
                'perPage' => 10,
            ], key('inventory-table'))

            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex justify-center">
                <a href="{{ route('vendas') }}" wire:navigate class="text-primary text-sm font-bold hover:underline">Ver todo o inventário ({{ \App\Models\Product::count() }}+ produtos)</a>
            </div>
        </div>
    </main>

    {{-- Product Modal --}}
    <x-modal name="product-modal" :show="$showModal" maxWidth="md">
        <form wire:submit.prevent="saveProduct" class="p-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">
                {{ $isEditing ? 'Editar Produto' : 'Novo Produto' }}
            </h2>

            <div class="space-y-4">
                <div>
                    <x-input-label for="name" value="Nome do Produto" />
                    <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="sku" value="SKU" />
                    <x-text-input id="sku" type="text" class="mt-1 block w-full" wire:model="sku" />
                    <x-input-error :messages="$errors->get('sku')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price" value="Preço (R$)" />
                        <x-text-input id="price" type="number" step="0.01" class="mt-1 block w-full" wire:model="price" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="stock" value="Estoque Inicial" />
                        <x-text-input id="stock" type="number" class="mt-1 block w-full" wire:model="stock" />
                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="category_id" value="Categoria" />
                    <select id="category_id" wire:model="category_id" class="mt-1 block w-full border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="image_url" value="URL da Imagem (Opcional)" />
                    <x-text-input id="image_url" type="text" class="mt-1 block w-full" wire:model="image_url" />
                    <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button wire:click="closeModal" type="button">
                    Cancelar
                </x-secondary-button>
                <x-primary-button class="bg-primary hover:bg-primary/90">
                    {{ $isEditing ? 'Salvar Alterações' : 'Criar Produto' }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    {{-- Footer Navigation --}}
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 fixed bottom-0 left-0 right-0 z-50">
        <nav class="max-w-md mx-auto flex justify-around items-center h-16 px-4">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex flex-col items-center gap-1 text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-[10px] font-medium">Dashboard</span>
            </a>
            <a href="{{ route('vendas') }}" wire:navigate class="flex flex-col items-center gap-1 text-primary">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="text-[10px] font-bold">Estoque</span>
            </a>
            <div class="flex flex-col items-center -mt-8">
                <button wire:click="checkout" class="bg-primary text-white rounded-full p-4 shadow-lg shadow-primary/30 border-4 border-white dark:border-slate-900">
                    <span class="material-symbols-outlined text-2xl">shopping_cart</span>
                </button>
            </div>
            <a href="{{ route('dashboard') }}" wire:navigate class="flex flex-col items-center gap-1 text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">bar_chart</span>
                <span class="text-[10px] font-medium">Relatórios</span>
            </a>
            <a href="{{ route('profile') }}" wire:navigate class="flex flex-col items-center gap-1 text-slate-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">person</span>
                <span class="text-[10px] font-medium">Perfil</span>
            </a>
        </nav>
    </footer>

    {{-- Toast Notifications --}}
    <div x-data="{ show: false, message: '' }" 
         x-on:message.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
         x-show="show" 
         class="fixed top-4 right-4 z-[100] bg-emerald-600 text-white px-6 py-3 rounded-xl shadow-xl transition-all"
         style="display: none;">
        <p x-text="message"></p>
    </div>
</div>