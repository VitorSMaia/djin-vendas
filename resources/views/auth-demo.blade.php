<x-auth.layout title="Login - Djin Vendas">
    <x-auth.card>
        <x-auth.header title="Djin Vendas" subtitle="Manage your sales with magic" />

        <form method="POST" action="#" class="space-y-6">
            @csrf

            <x-auth.input id="email" name="email" label="Email" type="email" icon="mail" placeholder="name@company.com"
                required autofocus />

            <div class="space-y-2">
                <div class="flex justify-between items-center ml-1">
                    <label for="password"
                        class="text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                    <a href="#" class="text-xs text-primary hover:underline font-medium">Forgot password?</a>
                </div>
                <x-auth.input id="password" name="password" type="password" icon="lock" placeholder="••••••••"
                    required />
            </div>

            <div class="flex items-center space-x-2 ml-1">
                <input id="remember" type="checkbox" name="remember"
                    class="w-4 h-4 rounded border-slate-300 dark:border-primary/30 text-primary focus:ring-primary bg-transparent">
                <label for="remember" class="text-sm text-slate-600 dark:text-slate-400">Remember this device</label>
            </div>

            <x-auth.button icon-end="arrow_forward">
                Enter Dashboard
            </x-auth.button>
        </form>

        <x-auth.footer>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Don't have an account yet?
                <a href="#" class="text-primary font-semibold hover:underline">Start free trial</a>
            </p>
        </x-auth.footer>
    </x-auth.card>
</x-auth.layout>