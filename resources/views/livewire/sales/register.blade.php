<div class="min-h-screen bg-slate-50 pb-32">
    <!-- Header -->
    <header class="bg-indigo-600 text-white p-4 shadow-lg sticky top-0 z-10">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold italic">Djin: Cadastro de Venda</h1>
            <div class="bg-indigo-500 px-3 py-1 rounded-full text-sm font-medium">
                Total: R$ {{ number_format($total, 2, ',', '.') }}
            </div>
        </div>
    </header>

    <!-- Messages -->
    @if (session()->has('message'))
        <div class="m-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="m-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Products List (Mobile-First) -->
    <div class="p-4 space-y-4">
        @foreach($products as $product)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 transition-transform active:scale-98">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $product->name }}</h3>
                        <p class="text-indigo-600 font-bold">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase">Estoque</span>
                        <span
                            class="text-sm font-black {{ $product->stock < 5 ? 'text-red-500 animate-pulse' : 'text-slate-600' }}">
                            {{ $product->stock }}
                        </span>
                    </div>
                </div>

                <!-- Large Control Buttons -->
                <div class="flex items-center gap-4">
                    <button wire:click="removeFromCart({{ $product->id }})"
                        class="flex-1 h-14 flex items-center justify-center bg-slate-100 rounded-2xl text-slate-600 active:bg-slate-200 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>

                    <span class="text-3xl font-black text-slate-800 w-12 text-center">
                        {{ $cart[$product->id] ?? 0 }}
                    </span>

                    <button wire:click="addToCart({{ $product->id }})"
                        class="flex-1 h-14 flex items-center justify-center bg-indigo-600 rounded-2xl text-white active:bg-indigo-700 transition-colors shadow-md">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Bottom Actions -->
    <div
        class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 z-20 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
        <!-- Category Toggles -->
        <div class="flex gap-2 overflow-x-auto no-scrollbar mb-4">
            @foreach($categories as $category)
                <button wire:click="selectCategory({{ $category->id }})"
                    class="px-5 py-2 whitespace-nowrap rounded-full text-sm font-bold transition-all {{ $selectedCategoryId == $category->id ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <button wire:click="checkout" @if(empty($cart)) disabled @endif
            class="w-full h-16 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-2xl font-black text-xl shadow-xl active:translate-y-1 transition-all flex justify-between items-center px-8 disabled:opacity-50">
            <span>CONFIRMAR VENDA</span>
            <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
        </button>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</div>