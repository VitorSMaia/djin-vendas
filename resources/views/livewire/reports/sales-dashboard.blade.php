<div class="min-h-screen bg-slate-50 p-4 pb-20">
    <header class="mb-6">
        <h1 class="text-2xl font-black text-slate-800">Relatório de Vendas</h1>
        <p class="text-slate-500">Resumo visual de performance e estoque</p>
    </header>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
            <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Total Hoje</span>
            <p class="text-xl font-black text-indigo-600">R$ {{ number_format($totalToday, 2, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
            <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Total Semana</span>
            <p class="text-xl font-black text-teal-600">R$ {{ number_format($totalWeek, 2, ',', '.') }}</p>
        </div>
    </div>

    <!-- Stock Alerts -->
    @if($lowStockItems->count() > 0)
        <div class="mb-8">
            <h2 class="text-sm font-bold text-red-600 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                ALERTA DE ESTOQUE BAIXO
            </h2>
            <div class="space-y-2">
                @foreach($lowStockItems as $item)
                    <div class="bg-red-50 border border-red-100 p-3 rounded-xl flex justify-between items-center">
                        <span class="font-bold text-red-700">{{ $item->name }}</span>
                        <span class="bg-red-600 text-white px-2 py-0.5 rounded text-xs font-black">{{ $item->stock }}
                            unid.</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Sales by Product -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 italic font-bold text-slate-400 text-sm">Vendas por Produto</div>
        <div class="divide-y divide-slate-100">
            @foreach($salesByProduct as $sale)
                <div class="p-4 flex justify-between items-center">
                    <div>
                        <p class="font-bold text-slate-800">{{ $sale->product->name }}</p>
                        <p class="text-xs text-slate-400">{{ $sale->qty }} unidades vendidas</p>
                    </div>
                    <p class="font-black text-slate-700">R$ {{ number_format($sale->total, 2, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Navigation Menu (App style) -->
    <nav
        class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 h-16 flex items-center justify-around z-30 px-6">
        <a href="{{ route('vendas') }}" class="flex flex-col items-center text-slate-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="text-[10px] uppercase font-bold">Venda</span>
        </a>
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-indigo-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v2m3 3h2a2 2 0 012 2v5a2 2 0 01-2 2h-2a2 2 0 01-2-2v-5a2 2 0 012-2zm-3-3V7a2 2 0 012-2h4a2 2 0 012 2v12a2 2 0 01-2 2h-4a2 2 0 01-2-2v-5a2 2 0 012-2zm-3-3V7a2 2 0 012-2h4a2 2 0 012 2v12a2 2 0 01-2 2h-4a2 2 0 01-2-2v-5a2 2 0 012-2z">
                </path>
            </svg>
            <span class="text-[10px] uppercase font-bold">Relatório</span>
        </a>
    </nav>
</div>