@extends('layouts.app')

@section('title', 'Running Club — Live Cafe')

@push('styles')
    @include('running.partials.styles')
@endpush

@section('content')

<section class="rc-hero">
    <div class="rc-wrap rc-hero-inner">
        <div class="rc-hero-card">
            <p class="rc-eyebrow">Live Running Club</p>
            <h1 class="rc-hero-title">Runs and events</h1>
            <p class="rc-hero-lead">
                Everyone is welcome to join our running club! RSVP for upcoming runs 8 hours in advance to help our planning.
            </p>
        </div>
        <img class="rc-hero-photo"
             src="{{ asset('images/running/run-clubHolder (1).jpg') }}"
             alt="Live Running Club members together after a run"
             width="544" height="392">
    </div>
</section>

<div class="rc-wrap rc-body">

    @include('running.partials.admin-bar')

    @if ($announcement)
        <section class="rc-card rc-announcement" aria-labelledby="announcement-title">
            <div class="rc-announcement-head">
                <p class="rc-eyebrow">Announcements</p>
                @if ($announcement->is_pinned)
                    <span class="rc-badge rc-badge-pinned">Pinned</span>
                @endif
            </div>
            <h2 id="announcement-title" class="rc-announcement-title">{{ $announcement->title }}</h2>
            <p class="rc-announcement-body">{{ $announcement->description }}</p>
            @if ($announcement->event)
                <p class="rc-announcement-link">
                    <a href="{{ route('running.events.show', $announcement->event) }}" class="rc-btn rc-btn-outline rc-btn-small">See the {{ $announcement->event->noun() }}</a>
                </p>
            @endif
            <p class="rc-muted">
                Posted {{ $announcement->published_at?->diffForHumans() }} ·
                <a href="{{ route('running.announcements.index') }}">All announcements ({{ $announcementCount }})</a>
            </p>
        </section>
    @endif

    <div class="rc-section-head">
        <h2 class="rc-section-title">Upcoming <span class="rc-count">({{ $events->count() }})</span></h2>
        @auth
            <a href="{{ route('running.rsvp.index') }}" class="rc-link">My RSVPs</a>
        @endauth
    </div>

    @forelse ($events as $event)
        @include('running.partials.event-row', ['event' => $event, 'myRsvp' => $myRsvps->get($event->id)])
    @empty
        <p class="rc-empty">No upcoming Events</p>
    @endforelse

    @guest
        <p class="rc-footnote">
            <a href="{{ route('running.sign-in') }}">Sign in</a> or <a href="{{ route('running.join') }}">create an account</a> to RSVP.
        </p>
    @endguest
</div>

@endsection
