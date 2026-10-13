<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
    <link rel="icon" type="image/jpeg" href="{{ asset('images/livecafelogo.jpeg') }}">
</head>
<body class="min-h-screen bg-neutral-100 antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div class="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-md flex-col gap-6">
            
            <!-- Branded Logo Header -->
            <div class="flex flex-col items-center justify-center gap-2 text-center">
                <a href="{{ route('home') }}" wire:navigate>
                    <img src="{{ asset('images/livecafelogo.jpeg') }}" alt="Live Cafe Logo" class="h-14 w-auto object-contain rounded-md">
                </a>
            </div>

            <!-- This is where your register.blade.php form will load -->
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>

        </div>
    </div>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
