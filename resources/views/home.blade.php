<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Djin Vendas') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <!-- Header -->
    <header class="border-b border-slate-200 bg-white/80 backdrop-blur">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-application-logo class="w-8 h-8 text-indigo-600" />
                <span class="font-semibold text-slate-900">Djin Vendas</span>
            </div>

            <nav class="hidden sm:flex items-center gap-6 text-sm text-slate-600">
                <a href="#features" class="hover:text-slate-900 transition-colors">Funcionalidades</a>
                <a href="#how-it-works" class="hover:text-slate-900 transition-colors">Como funciona</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                    class="text-sm font-medium text-slate-700 hover:text-slate-900 transition-colors">
                    Entrar
                </a>
                <a href="{{ route('register') }}"
                    class="hidden sm:inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors">
                    Criar conta
                </a>
            </div>
        </div>
    </header>

    <!-- Hero / Main -->
    <main class="min-h-[calc(100vh-8rem)]">
        <section class="py-12 sm:py-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-12 lg:grid-cols-2 items-center">
                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-slate-900">
                        Controle suas vendas e estoque
                        <span class="text-indigo-600">em tempo real</span>.
                    </h1>
                    <p class="mt-4 text-base sm:text-lg text-slate-600">
                        Djin Vendas ajuda você a registrar vendas, acompanhar estoque e analisar resultados em um painel
                        simples e rápido, otimizado para uso no dia a dia.
                    </p>

                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-colors">
                            Acessar painel
                        </a>
                        <a href="#features"
                            class="inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                            Ver funcionalidades
                        </a>
                    </div>

                    <dl id="features" class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <dt class="font-semibold text-slate-900">Vendas rápidas</dt>
                            <dd class="mt-1 text-slate-600">Interface otimizada para registrar vendas em poucos toques.
                            </dd>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <dt class="font-semibold text-slate-900">Estoque em tempo real</dt>
                            <dd class="mt-1 text-slate-600">Alerta de estoque baixo e visão clara por produto.</dd>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <dt class="font-semibold text-slate-900">Relatórios</dt>
                            <dd class="mt-1 text-slate-600">Resumo diário e semanal de vendas diretamente no
                                dashboard.</dd>
                        </div>
                    </dl>
                </div>

                <div id="how-it-works"
                    class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 sm:p-8 space-y-4">
                    <h2 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">
                        Como funciona
                    </h2>
                    <ol class="space-y-4 text-sm text-slate-700">
                        <li class="flex gap-3">
                            <span
                                class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-xs font-semibold text-white">
                                1
                            </span>
                            <div>
                                <p class="font-semibold text-slate-900">Acesse com seu usuário</p>
                                <p class="text-slate-600">Faça login para entrar no painel seguro do Djin Vendas.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span
                                class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-xs font-semibold text-white">
                                2
                            </span>
                            <div>
                                <p class="font-semibold text-slate-900">Cadastre produtos e categorias</p>
                                <p class="text-slate-600">Organize seu catálogo com preços, estoque e categorias.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span
                                class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-xs font-semibold text-white">
                                3
                            </span>
                            <div>
                                <p class="font-semibold text-slate-900">Registre vendas no dia a dia</p>
                                <p class="text-slate-600">Use a interface de vendas para lançar pedidos e acompanhar o
                                    resultado.</p>
                            </div>
                        </li>
                    </ol>

                    <div class="mt-6 rounded-xl bg-slate-50 border border-dashed border-slate-200 p-4 text-xs text-slate-600">
                        <p><span class="font-semibold">Dica:</span> após criar sua conta, acesse o painel em
                            <span class="font-mono text-slate-800">/dashboard</span> para ver os indicadores de vendas
                            e estoque.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} Djin Vendas. Todos os direitos reservados.</p>
            <p>Construído com Laravel, Livewire e Tailwind CSS.</p>
        </div>
    </footer>
</body>

</html>

