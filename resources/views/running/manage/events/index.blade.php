@extends('layouts.app')

@section('title', 'Runs & events — Live Cafe admin')

@push('styles')
    @include('running.partials.styles')
    @include('running.manage.partials.styles')
@endpush

@section('content')

<div class="rc-wrap">
    @include('running.manage.partials.header', [
        'title' => 'Runs & events',
        'active' => 'events',
        'actions' => [
            [route('running.manage.events.create'), 'Add a run or event'],
            [route('running.manage.announcements.create'), 'Write an announcement'],
        ],
    ])

    <section class="rc-manage-section" aria-labelledby="upcoming-heading">
        <h2 id="upcoming-heading">Upcoming</h2>

        @if ($upcoming->isEmpty())
            <p class="rc-empty">Nothing on the calendar. <a href="{{ route('running.manage.events.create') }}" class="rc-link">Add a run or event</a></p>
        @else
            <div class="rc-table-wrap">
                <table class="rc-table">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Run or event</th>
                            <th scope="col">Expected</th>
                            <th scope="col">RSVPs</th>
                            <th scope="col"><span class="rc-sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($upcoming as $event)
                            <tr>
                                <td class="is-number">
                                    {{ $event->event_date->format('D j M') }}
                                    <span class="rc-sub">{{ $event->startTimeLabel() }}</span>
                                </td>
                                <td>
                                    <strong>{{ $event->title }}</strong>
                                    <span class="rc-sub">
                                        {{ collect([$event->typeLabel(), $event->distanceLabel(), $event->sponsor ? 'Presented by '.$event->sponsor : null])->filter()->implode(' · ') }}
                                    </span>
                                    <span class="rc-sub">{{ $event->address }}</span>
                                </td>
                                <td class="is-number">
                                    {{ $event->total_attendees }} {{ \Illuminate\Support\Str::plural('person', $event->total_attendees) }}
                                    <span class="rc-sub">{{ $event->members_going }} {{ \Illuminate\Support\Str::plural('member', $event->members_going) }} + {{ $event->total_attendees - $event->members_going }} extra</span>
                                </td>
                                <td>
                                    @include('running.partials.rsvp-status', ['event' => $event, 'myRsvp' => null])
                                </td>
                                <td>
                                    <div class="rc-actions">
                                        <a href="{{ route('running.manage.events.rsvps', $event) }}">RSVPs</a>
                                        <a href="{{ route('running.manage.events.edit', $event) }}">Edit</a>
                                        <a href="{{ route('running.manage.events.create', ['from' => $event->id]) }}">Duplicate</a>
                                        @unless ($event->isCancelled())
                                            <form method="POST" action="{{ route('running.manage.events.cancel', $event) }}"
                                                  onsubmit="return confirm('Cancel {{ addslashes($event->title) }}? Everyone who is going will get an email.');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rc-btn-text is-danger">Cancel</button>
                                            </form>
                                        @endunless
                                        @if ($event->rsvps_count === 0)
                                            <form method="POST" action="{{ route('running.manage.events.destroy', $event) }}"
                                                  onsubmit="return confirm('Delete {{ addslashes($event->title) }}? This can\'t be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rc-btn-text is-danger">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="rc-manage-section" aria-labelledby="past-heading">
        <h2 id="past-heading">Past</h2>

        @if ($past->isEmpty())
            <p class="rc-empty">No past runs yet.</p>
        @else
            <div class="rc-table-wrap">
                <table class="rc-table">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Run or event</th>
                            <th scope="col">Expected</th>
                            <th scope="col"><span class="rc-sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($past as $event)
                            <tr>
                                <td class="is-number">
                                    {{ $event->event_date->format('D j M Y') }}
                                    <span class="rc-sub">{{ $event->startTimeLabel() }}</span>
                                </td>
                                <td>
                                    <strong>{{ $event->title }}</strong>
                                    @if ($event->isCancelled())
                                        <span class="rc-badge rc-badge-cancelled">Cancelled</span>
                                    @endif
                                    <span class="rc-sub">{{ $event->address }}</span>
                                </td>
                                <td class="is-number">
                                    {{ $event->total_attendees }} {{ \Illuminate\Support\Str::plural('person', $event->total_attendees) }}
                                    <span class="rc-sub">{{ $event->members_going }} {{ \Illuminate\Support\Str::plural('member', $event->members_going) }} + {{ $event->total_attendees - $event->members_going }} extra</span>
                                </td>
                                <td>
                                    <div class="rc-actions">
                                        <a href="{{ route('running.manage.events.rsvps', $event) }}">RSVPs</a>
                                        <a href="{{ route('running.manage.events.create', ['from' => $event->id]) }}">Duplicate</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>

@endsection
