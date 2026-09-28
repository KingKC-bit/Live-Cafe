<x-layouts::auth :title="__('Create account')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Create an account')"
            :description="__('Enter your details below to create your account')"
        />

        <form method="POST" action="/register" class="flex flex-col gap-6">
            @csrf

            <!-- First Name -->
            <flux:input
                name="name"
                :label="__('First name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="given-name"
                placeholder="Jane"
            />

            <!-- Surname -->
            <flux:input
                name="surname"
                :label="__('Surname')"
                :value="old('surname')"
                type="text"
                required
                autocomplete="family-name"
                placeholder="Doe"
            />

            <!-- Email -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Phone -->
            <flux:input
                name="phone_number"
                :label="__('Phone number')"
                :value="old('phone_number')"
                type="text"
                required
                autocomplete="tel"
                placeholder="+27 81 234 5678"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            @if ($errors->any())
                <div class="text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </form>

        <div class="text-center text-sm text-zinc-600">
            Already have an account?
            <flux:link :href="route('login')" wire:navigate>Sign in</flux:link>
        </div>
    </div>
</x-layouts::auth>