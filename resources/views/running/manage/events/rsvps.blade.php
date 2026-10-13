@extends('layouts.app')

@php
    $extras = $going->sum('extras');
@endphp

@section('title', 'RSVPs for '.$event->title.' — Live Cafe admin')

@push('styles')
    @include('running.partials.styles')
    @include('running.manage.partials.styles')
@endpush

@section('content')

<div class="rc-wrap">
    @include('running.manage.partials.header', [
        'title' => 'RSVPs for '.$event->title,
        'active' => 'events',
        'actions' => [[route('running.manage.events.rsvps.export', $event), 'Download CSV']],
    ])

    <p class="rc-muted rc-manage-intro">
        {{ collect([$event->typeLabel(), $event->event_date->format('l j F Y'), $event->startTimeLabel(), $event->address])->implode(' · ') }}
        @if ($event->isCancelled())
            <span class="rc-badge rc-badge-cancelled">Cancelled</span>
        @endif
    </p>

    <div class="rc-stats">
        <div class="rc-stat">
            <div class="rc-stat-label">Expected headcount</div>
            <div class="rc-stat-value">{{ $going->count() + $extras }}</div>
        </div>
        <div class="rc-stat">
            <div class="rc-stat-label">Members going</div>
            <div class="rc-stat-value">{{ $going->count() }}</div>
        </div>
        <div class="rc-stat">
            <div class="rc-stat-label">{{ ucfirst($event->extrasNoun()) }}</div>
            <div class="rc-stat-value">{{ $extras }}</div>
        </div>
        <div class="rc-stat">
            <div class="rc-stat-label">Cancelled</div>
            <div class="rc-stat-value">{{ $cancelled->count() }}</div>
        </div>
    </div>

    <p class="rc-muted rc-manage-intro">
        The headcount is a guide for planning, not a guest list.
        @unless ($event->isCancelled())
            RSVPs {{ $event->rsvpIsOpen() ? 'close' : 'closed' }} {{ $event->rsvpClosesAt()->format('D j M') }} at {{ $event->rsvpClosesAt()->format('H:i') }}.
        @endunless
    </p>

    <section class="rc-manage-section" aria-labelledby="going-heading">
        <h2 id="going-heading">Going</h2>

        @if ($going->isEmpty())
            <p class="rc-empty">No RSVPs yet.</p>
        @else
            <div class="rc-table-wrap">
                <table class="rc-table">
                    <thead>
                        <tr>
                            <th scope="col">Member</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">{{ ucfirst($event->extrasNoun()) }}</th>
                            <th scope="col">RSVP'd</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($going as $rsvp)
                            <tr>
                                <td><strong>{{ $rsvp->user->name }} {{ $rsvp->user->surname }}</strong></td>
                                <td><a href="mailto:{{ $rsvp->user->email }}" class="rc-link">{{ $rsvp->user->email }}</a></td>
                                <td>{{ $rsvp->user->phone_number ?: '—' }}</td>
                                <td class="is-number">{{ $rsvp->extras }}</td>
                                <td class="is-number">{{ $rsvp->created_at?->format('D j M, H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    @if ($cancelled->isNotEmpty())
        <section class="rc-manage-section" aria-labelledby="cancelled-heading">
            <h2 id="cancelled-heading">Cancelled their RSVP</h2>

            <div class="rc-table-wrap">
                <table class="rc-table">
                    <thead>
                        <tr>
                            <th scope="col">Member</th>
                            <th scope="col">Email</th>
                            <th scope="col">Cancelled</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cancelled as $rsvp)
                            <tr>
                                <td>{{ $rsvp->user->name }} {{ $rsvp->user->surname }}</td>
                                <td><a href="mailto:{{ $rsvp->user->email }}" class="rc-link">{{ $rsvp->user->email }}</a></td>
                                <td class="is-number">{{ $rsvp->cancelled_at?->format('D j M, H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    <a href="{{ route('running.manage.index') }}" class="rc-back">&larr; Back to runs &amp; events</a>
</div>

@endsection
