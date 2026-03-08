<div>
    <form wire:submit="register" class="space-y-6">
        @csrf

        <x-auth.input wire:model="name" id="name" name="name" label="Name" type="text" icon="person" required autofocus
            autocomplete="name" />

        <x-auth.input wire:model="email" id="email" name="email" label="Email" type="email" icon="mail" required
            autocomplete="username" />

        <x-auth.input wire:model="password" id="password" name="password" label="Password" type="password" icon="lock"
            required autocomplete="new-password" />

        <x-auth.input wire:model="password_confirmation" id="password_confirmation" name="password_confirmation"
            label="Confirm Password" type="password" icon="lock_reset" required autocomplete="new-password" />

        <div class="flex items-center justify-between">
            <a class="text-sm text-primary hover:underline font-medium" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-auth.button type="submit" icon-end="arrow_forward">
                {{ __('Register') }}
            </x-auth.button>
        </div>
    </form>
</div>