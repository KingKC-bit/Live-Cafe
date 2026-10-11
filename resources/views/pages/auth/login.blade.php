<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
<<<<<<< HEAD
        
        <!-- Clean unified header context without custom head injections -->
        <x-auth-header 
            :title="__('Log in to your account')" 
            :description="__('Enter your email and password below to log in')" 
        />
=======
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

<<<<<<< HEAD
=======

>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
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
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
<<<<<<< HEAD
            <flux:checkbox name="remember" :label="__('Remember me')" />
=======
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

<<<<<<< HEAD
        <div class="text-center text-sm text-zinc-600">
            Don't have an account?
            <flux:link :href="route('register')" wire:navigate>Sign up</flux:link>
        </div>
=======
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
    </div>
</x-layouts::auth>
