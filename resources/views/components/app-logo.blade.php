@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand :name="config('app.name', 'Laravel')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
<<<<<<< HEAD
            <x-app-logo-icon class="size-8 object-cover rounded-md" />
=======
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="config('app.name', 'Laravel')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
<<<<<<< HEAD
            <x-app-logo-icon class="size-8 object-cover rounded-md" />
        </x-slot>
    </flux:brand>
@endif

{{-- Full brand logo image (Replaces the large default Laravel SVG) --}}
<img 
    src="{{ asset("/images/livecafelogo.jpeg") }}" 
    alt="Live Cafe Logo" 
    {{ $attributes->merge(['class' => 'h-10 w-auto object-contain']) }}
/>
=======
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:brand>
@endif
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
