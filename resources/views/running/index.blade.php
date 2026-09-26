@extends('layouts.app')

@section('title', 'Running Club — Live Cafe')

@push('styles')
<style>
    .page-header {
        background: var(--green);
        padding: 3.5rem 2rem 3rem;
    }

    .page-header-inner {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header h1 {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 3.5vw, 2.8rem);
        color: var(--white);
        margin-bottom: 0.4rem;
    }

    .page-header p { color: rgba(255,255,255,0.7); font-size: 0.95rem; }

    .running-body {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 2rem;
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 3rem;
        align-items: start;
    }

    /* Events list */
    .events-section h2 {
        font-family: var(--font-display);
        font-size: 1.5rem;
        color: var(--green);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1.5px solid var(--sage);
    }

    .event-card {
        background: var(--white);
        border-left: 4px solid var(--green);
        padding: 1.5rem;
        margin-bottom: 1px;
        display: grid;
        grid-template-columns: 80px 1fr auto;
        gap: 1.25rem;
        align-items: start;
    }

    .event-card:hover { background: var(--base); }

    .event-card-date {
        text-align: center;
        background: var(--green);
        color: var(--white);
        padding: 0.6rem 0.4rem;
    }

    .event-card-date .month {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        opacity: 0.8;
    }

    .event-card-date .day {
        font-family: var(--font-display);
        font-size: 1.7rem;
        line-height: 1;
    }

    .event-card-date .weekday {
        font-size: 0.65rem;
        opacity: 0.7;
        margin-top: 2px;
    }

    .event-card-body h3 {
        font-family: var(--font-display);
        font-size: 1.05rem;
        color: var(--green);
        margin-bottom: 0.3rem;
    }

    .event-card-meta {
        font-size: 0.82rem;
        color: var(--slate);
        margin-bottom: 0.4rem;
    }

    .event-card-meta span + span::before {
        content: ' · ';
        color: #ccc;
    }

    .event-card-attendees {
        font-size: 0.8rem;
        color: var(--slate);
    }

    .event-card-attendees strong { color: var(--green); }

    .btn-rsvp {
        display: inline-block;
        background: var(--accent);
        color: var(--ink);
        padding: 0.5rem 1.1rem;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        border-radius: 3px;
        white-space: nowrap;
        font-family: var(--font-body);
        transition: opacity 0.15s;
        align-self: center;
    }

    .btn-rsvp:hover { opacity: 0.85; }

    .btn-rsvp.rsvpd {
        background: var(--sage);
        color: var(--green);
    }

    /* Announcements sidebar */
    .sidebar h2 {
        font-family: var(--font-display);
        font-size: 1.2rem;
        color: var(--green);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1.5px solid var(--sage);
    }

    .announcement-item {
        background: var(--white);
        padding: 1.1rem 1.25rem;
        margin-bottom: 1px;
        border-left: 3px solid var(--accent);
    }

    .announcement-item p {
        font-size: 0.875rem;
        color: var(--slate);
        line-height: 1.55;
    }

    .announcement-item time {
        font-size: 0.72rem;
        color: #aaa;
        display: block;
        margin-bottom: 0.3rem;
    }

    .sidebar-all {
        display: inline-block;
        margin-top: 1rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--green);
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
        padding-bottom: 1px;
    }

    .empty-state {
        padding: 2rem 0;
        color: var(--slate);
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .running-body { grid-template-columns: 1fr; }
        .event-card { grid-template-columns: 60px 1fr; }
        .btn-rsvp { grid-column: 1 / -1; }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <h1>Running Club</h1>
        <p>Saturday runs from Live Cafe, Sandton. Everyone welcome.</p>
    </div>
</div>

<div class="running-body">

    {{-- Events --}}
    <section class="events-section">
        <h2>Upcoming runs</h2>

        @forelse($events as $event)
            @php
                $date = \Carbon\Carbon::parse($event->event_date);
                $userRsvpd = auth()->check() &&
                    $event->rsvps->where('user_id', auth()->id())->isNotEmpty();
            @endphp
            <div class="event-card">
                <div class="event-card-date">
                    <div class="month">{{ $date->format('M') }}</div>
                    <div class="day">{{ $date->format('d') }}</div>
                    <div class="weekday">{{ $date->format('D') }}</div>
                </div>

                <div class="event-card-body">
                    <h3>{{ $event->description ?? 'Saturday Run' }}</h3>
                    <p class="event-card-meta">
                        <span>{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</span>
                        <span>{{ $event->address }}</span>
                    </p>
                    <p class="event-card-attendees">
                        <strong>{{ $event->total_attendees }}</strong> attending
                    </p>
                </div>

                @auth
                    @if($userRsvpd)
                        <span class="btn-rsvp rsvpd">RSVPd</span>
                    @else
                        <a href="{{ route('running.events.show', $event) }}" class="btn-rsvp">RSVP</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-rsvp">Sign in to RSVP</a>
                @endauth
            </div>
        @empty
            <p class="empty-state">No upcoming runs scheduled. Check back soon.</p>
        @endforelse
    </section>

    {{-- Announcements sidebar --}}
    <aside class="sidebar">
        <h2>Latest news</h2>

        @forelse($announcements as $announcement)
            <div class="announcement-item">
                <time>{{ $announcement->created_at->diffForHumans() }}</time>
                <p>{{ Str::limit($announcement->description, 120) }}</p>
            </div>
        @empty
            <p style="font-size:0.875rem;color:var(--slate);">No announcements yet.</p>
        @endforelse

        <a href="{{ route('running.announcements.index') }}" class="sidebar-all">All announcements</a>
    </aside>

</div>

@endsection