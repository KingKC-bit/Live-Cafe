@extends('layouts.app')

@section('title', 'Announcements — Live Cafe')

@push('styles')
    @include('running.partials.styles')
@endpush

@section('content')

<div class="rc-wrap">
    <a href="{{ route('running.index') }}" class="rc-back">&larr; Runs and events</a>

    <header class="rc-page-head">
        <p class="rc-eyebrow">Live Cafe</p>
        <h1 class="rc-page-title">Announcements</h1>
        <p class="rc-page-lead">News from the cafe, from new flavours to run club updates.</p>
    </header>

    @include('running.partials.admin-bar')

    @forelse ($announcements as $announcement)
        <article class="rc-list-card">
            @if ($announcement->is_pinned)
                <span class="rc-badge rc-badge-pinned">Pinned</span>
            @endif
            <h2><a href="{{ route('running.announcements.show', $announcement) }}">{{ $announcement->title }}</a></h2>
            <p class="rc-announcement-body">{{ $announcement->description }}</p>
            <p class="rc-muted">
                Posted {{ $announcement->published_at?->format('j F Y') }}
                @if ($announcement->event)
                    · <a href="{{ route('running.events.show', $announcement->event) }}">See the {{ $announcement->event->noun() }}</a>
                @endif
            </p>
        </article>
    @empty
        <p class="rc-empty">No announcements yet.</p>
    @endforelse
</div>

@endsection
