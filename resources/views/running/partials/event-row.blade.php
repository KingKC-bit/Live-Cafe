{{-- One run or event in a list. Expects $event and $myRsvp (the member's RSVP or null). --}}
<article class="rc-row {{ $event->isCancelled() ? 'is-cancelled' : '' }}">
    <div class="rc-date" aria-hidden="true">
        <span class="rc-date-day">{{ strtoupper($event->event_date->format('D')) }}</span>
        <span class="rc-date-num">{{ $event->event_date->format('j') }}</span>
        <span class="rc-date-month">{{ strtoupper($event->event_date->format('M')) }}</span>
    </div>

    <div class="rc-row-main">
        <h3 class="rc-row-title">
            <a href="{{ route('running.events.show', $event) }}">{{ $event->title }}</a>
        </h3>
        <p class="rc-row-meta">
            <span class="rc-sr-only">{{ $event->event_date->format('l j F') }},</span>
            {{ collect([$event->startTimeLabel(), $event->distanceLabel(), $event->pace])->filter()->implode(' · ') }}
        </p>

        @if (! $event->isRun() || $event->sponsor)
            <div class="rc-row-badges">
                @unless ($event->isRun())
                    <span class="rc-badge rc-badge-type">{{ $event->typeLabel() }}</span>
                @endunless
                @if ($event->sponsor)
                    <span class="rc-badge rc-badge-sponsor">Presented by {{ $event->sponsor }}</span>
                @endif
            </div>
        @endif
    </div>

    <div class="rc-row-side">
        @include('running.partials.rsvp-status', ['event' => $event, 'myRsvp' => $myRsvp])
        <a href="{{ route('running.events.show', $event) }}" class="rc-btn rc-btn-outline">View<span class="rc-sr-only"> {{ $event->title }}</span></a>
    </div>
</article>
