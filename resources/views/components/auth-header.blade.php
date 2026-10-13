@props([
    'title',
    'description' => null,
    'eyebrow' => __('Your account'),
])

<div class="flex w-full flex-col gap-2">
    <p class="auth-kicker">{{ $eyebrow }}</p>
    <h1 class="auth-title">{{ $title }}</h1>
    @if ($description)
        <p class="auth-lead">{{ $description }}</p>
    @endif
</div>
