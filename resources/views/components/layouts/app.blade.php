<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="pb-20">
            {{ $slot }}
        </main>

        @auth
            <!-- Global Bottom Navigation Menu -->
            <nav
                class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 h-16 flex items-center justify-around z-30 px-6 sm:justify-center sm:gap-16 pb-safe shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <a href="{{ route('vendas') }}"
                    class="flex flex-col items-center {{ request()->routeIs('vendas') ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-500' }} transition-colors"
                    wire:navigate>
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-[10px] uppercase font-bold tracking-wider">Venda</span>
                </a>

                <a href="{{ route('dashboard') }}"
                    class="flex flex-col items-center {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-500' }} transition-colors"
                    wire:navigate>
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v2m3 3h2a2 2 0 012 2v5a2 2 0 01-2 2h-2a2 2 0 01-2-2v-5a2 2 0 012-2zm-3-3V7a2 2 0 012-2h4a2 2 0 012 2v12a2 2 0 01-2 2h-4a2 2 0 01-2-2v-5a2 2 0 012-2zm-3-3V7a2 2 0 012-2h4a2 2 0 012 2v12a2 2 0 01-2 2h-4a2 2 0 01-2-2v-5a2 2 0 012-2z">
                        </path>
                    </svg>
                    <span class="text-[10px] uppercase font-bold tracking-wider">Relatório</span>
                </a>

                <a href="{{ route('estoque') }}"
                    class="flex flex-col items-center {{ request()->routeIs('estoque') ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-500' }} transition-colors"
                    wire:navigate>
                    <span class="material-symbols-outlined text-2xl leading-none mb-1">inventory_2</span>
                    <span class="text-[10px] uppercase font-bold tracking-wider">Estoque</span>
                </a>
            </nav>
        @endauth
    </div>
</body>

</html>