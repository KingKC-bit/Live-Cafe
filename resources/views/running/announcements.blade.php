@extends('layouts.app')

@section('title', 'Announcements — Live Running Club')

@push('styles')
    @include('running.partials.styles')
@endpush

@section('content')

<div class="rc-wrap">
    <a href="{{ route('running.index') }}" class="rc-back">&larr; All runs</a>

    <header class="rc-page-head">
        <p class="rc-eyebrow">Live Running Club</p>
        <h1 class="rc-page-title">Announcements</h1>
    </header>

    @forelse ($announcements as $announcement)
        <article class="rc-list-card">
            @if ($announcement->is_pinned)
                <span class="rc-badge rc-badge-pinned">Pinned</span>
            @endif
            <h2><a href="{{ route('running.announcements.show', $announcement) }}">{{ $announcement->title }}</a></h2>
            <p class="rc-announcement-body">{{ $announcement->description }}</p>
            <p class="rc-muted">Posted {{ $announcement->published_at?->format('j F Y') }}</p>
        </article>
    @empty
        <p class="rc-empty">No announcements yet.</p>
    @endforelse
</div>

@endsection
