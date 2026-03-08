<div class="p-4 space-y-6">
    <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Gestão de Estoque</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Baixas manuais, reposição e avarias.</p>
        </div>

        <div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar produto..."
                class="w-full sm:w-64 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
            role="alert">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($products as $product)
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ $product->name }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Estoque: <span
                                    class="font-bold {{ $product->stock <= 5 ? 'text-red-500' : 'text-green-500' }}">{{ $product->stock }}
                                    un</span></p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-indigo-600 dark:text-indigo-400">R$
                                {{ number_format($product->price, 2, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <button wire:click="openModal({{ $product->id }}, 'IN')"
                            class="flex flex-col items-center justify-center p-3 h-16 bg-green-100 text-green-700 hover:bg-green-200 rounded-xl transition-colors">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </button>
                        <button wire:click="openModal({{ $product->id }}, 'OUT_SALE')"
                            class="flex flex-col items-center justify-center p-3 h-16 bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 rounded-xl transition-colors">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <button wire:click="openModal({{ $product->id }}, 'OUT_LOSS')"
                            class="flex flex-col items-center justify-center p-3 h-16 bg-red-100 text-red-700 hover:bg-red-200 rounded-xl transition-colors">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500">
                Nenhum produto encontrado.
            </div>
        @endforelse
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-sm w-full overflow-hidden">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                        @if($actionType === 'IN') Entrada (Reposição)
                        @elseif($actionType === 'OUT_SALE') Saída (Venda Manual)
                        @else Avaria/Perda @endif
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="p-4 space-y-4">
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">{{ $selectedProduct->name }}</p>
                        <p class="text-sm text-slate-500">Estoque atual: {{ $selectedProduct->stock }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Quantidade</label>
                        <div class="flex items-center space-x-2">
                            <button type="button" wire:click="$set('quantity', {{ max(1, $quantity - 1) }})"
                                class="p-3 bg-slate-100 dark:bg-slate-700 rounded-xl touch-manipulation">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                            </button>
                            <input type="number" wire:model="quantity" min="1"
                                class="w-full text-center text-xl font-bold rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white h-12">
                            <button type="button" wire:click="$set('quantity', {{ $quantity + 1 }})"
                                class="p-3 bg-slate-100 dark:bg-slate-700 rounded-xl touch-manipulation">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        @error('quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    @if($actionType === 'OUT_LOSS')
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Motivo da Avaria
                                (Obrigatório)</label>
                            <input type="text" wire:model="reason" placeholder="Ex: Derreteu, Rasgou a embalagem..."
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            @error('reason') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Observação
                                (Opcional)</label>
                            <input type="text" wire:model="reason" placeholder="Opcional..."
                                class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>
                    @endif
                </div>

                <div
                    class="p-4 bg-slate-50 dark:bg-slate-700/50 border-t border-slate-200 dark:border-slate-700 flex space-x-3">
                    <button wire:click="closeModal"
                        class="flex-1 px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl font-medium">Cancelar</button>
                    <button wire:click="submitMovement"
                        class="flex-1 px-4 py-3 {{ $actionType === 'IN' ? 'bg-green-600 hover:bg-green-700' : ($actionType === 'OUT_LOSS' ? 'bg-red-600 hover:bg-red-700' : 'bg-indigo-600 hover:bg-indigo-700') }} text-white rounded-xl font-medium shadow-sm transition-colors">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>