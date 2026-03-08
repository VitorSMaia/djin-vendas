<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        @csrf
        <x-auth.input wire:model="form.email" id="email" name="form.email" label="Email" type="email" icon="mail"
            required autofocus />

        <x-auth.input wire:model="form.password" id="password" name="form.password" label="Password" type="password"
            icon="lock" required />

        <x-auth.button type="submit" icon-end="arrow_forward">
            Enter Dashboard
        </x-auth.button>
    </form>
</div>