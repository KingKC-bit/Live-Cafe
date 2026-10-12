@props([
    'title',
    'description' => null,
    'eyebrow' => __('Your account'),
])

<div class="flex w-full flex-col gap-2">
    <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#632E62]">{{ $eyebrow }}</p>
    <h1 class="text-3xl font-extrabold text-[#17131B]">{{ $title }}</h1>
    @if ($description)
        <p class="text-sm text-[#5F5A66]">{{ $description }}</p>
    @endif
</div>
