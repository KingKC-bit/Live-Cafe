@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand :name="config('app.name', 'Laravel')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-8 object-cover rounded-md" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="config('app.name', 'Laravel')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
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
