<x-layouts::auth :title="__('Sign in')">
    <div class="flex flex-col gap-6">
        
        <x-auth-header :title="__('Sign in')" />

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Remember Me + Forgot password -->
            <div class="flex items-center justify-between gap-4">
                <flux:checkbox name="remember" :label="__('Remember me')" />

                @if (Route::has('password.request'))
                    <flux:link class="text-sm" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Sign in') }}
                </flux:button>
            </div>
        </form>

        <div class="text-sm text-[#5F5A66]">
            {{ __('No account yet?') }}
            <flux:link :href="route('register')" wire:navigate>{{ __('Create one') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
