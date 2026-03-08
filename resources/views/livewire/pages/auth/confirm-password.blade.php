<div>
    <div class="mb-4 text-sm text-slate-600 dark:text-slate-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form wire:submit="confirmPassword" class="space-y-6">
        @csrf

        <x-auth.input wire:model="password" id="password" name="password" label="Password" type="password" icon="lock"
            required autocomplete="current-password" />

        <div class="flex items-center justify-end">
            <x-auth.button type="submit" icon-end="check_circle">
                {{ __('Confirm') }}
            </x-auth.button>
        </div>
    </form>
</div>