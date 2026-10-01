@extends('layouts.app')

@section('title', $announcement->title.' — Live Cafe')

@push('styles')
    @include('running.partials.styles')
@endpush

@section('content')

<div class="rc-wrap">
    <a href="{{ route('running.announcements.index') }}" class="rc-back">&larr; All announcements</a>

    <article>
        <header class="rc-page-head">
            <p class="rc-eyebrow">Announcement · {{ $announcement->published_at?->format('j F Y') }}</p>
            <h1 class="rc-page-title">{{ $announcement->title }}</h1>
        </header>

        <p class="rc-prose rc-article-body">{!! nl2br(e($announcement->description)) !!}</p>

        @if ($announcement->event)
            <p class="rc-announcement-link">
                <a href="{{ route('running.events.show', $announcement->event) }}" class="rc-btn rc-btn-primary">See the {{ $announcement->event->noun() }}: {{ $announcement->event->title }}</a>
            </p>
        @endif
    </article>
</div>

@endsection
