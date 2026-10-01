{{--
    The RSVP box on a run's page. Expects $event and $myRsvp.

    An RSVP only gives the organisers a rough headcount, so the wording stays
    neutral: it says when RSVPs close, never that someone can't come.
--}}
@php
    $noun = $event->isRun() ? 'run' : 'event';
    $closesAt = $event->rsvpClosesAt()->format('D j M, H:i');
    $user = auth()->user();
@endphp

<section id="rsvp" class="rc-card rc-rsvp" aria-labelledby="rsvp-heading">
    <h2 id="rsvp-heading" class="rc-rsvp-title">RSVP</h2>

    @if ($event->isCancelled())
        <p>This {{ $noun }} has been cancelled.</p>

    @elseif ($event->hasStarted())
        @if ($event->event_date->isToday())
            <p>This {{ $noun }} has already started.</p>
        @else
            <p>This {{ $noun }} took place on {{ $event->event_date->format('l j F') }}.</p>
        @endif

    @elseif (! $user)
        @if ($event->rsvpIsOpen())
            <p>Let us know you're coming. RSVPs close {{ $closesAt }}.</p>
            <a href="{{ route('running.sign-in', ['event' => $event->id]) }}" class="rc-btn rc-btn-primary rc-btn-block">Sign in to RSVP</a>
            <p class="rc-muted rc-rsvp-alt">
                New here? <a href="{{ route('running.join', ['event' => $event->id]) }}">Create an account</a>
            </p>
        @else
            <p>RSVPs for this {{ $noun }} have closed.</p>
        @endif

    @elseif (! $user->hasVerifiedEmail())
        <p>Verify your email address to RSVP. We sent you a link when you signed up.</p>
        <a href="{{ route('verification.notice') }}" class="rc-btn rc-btn-outline rc-btn-block">Verify my email</a>

    @elseif ($myRsvp?->isGoing())
        <p class="rc-going">
            You're going{{ $myRsvp->extras > 0 ? ' with '.$myRsvp->extras.' extra '.\Illuminate\Support\Str::plural('runner', $myRsvp->extras) : '' }}.
        </p>

        <form method="POST" action="{{ route('running.rsvp.store', $event) }}">
            @csrf
            <label class="rc-label" for="extras">Extra runners</label>
            <span class="rc-hint" id="extras-hint">
                @if ($event->rsvpIsOpen())
                    How many people you're bringing. No names needed.
                @else
                    RSVPs have closed, but you can still bring fewer people.
                @endif
            </span>
            @include('running.partials.stepper', [
                'value' => $myRsvp->extras,
                'max' => $event->rsvpIsOpen() ? 99 : $myRsvp->extras,
            ])
            @error('extras') <p class="rc-error">{{ $message }}</p> @enderror
            <button type="submit" class="rc-btn rc-btn-primary rc-btn-block">Update my RSVP</button>
        </form>

        <form method="POST" action="{{ route('running.rsvp.destroy', $event) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="rc-btn-text is-danger">Cancel my RSVP</button>
        </form>

    @elseif ($event->rsvpIsOpen())
        <p>RSVPs close {{ $closesAt }}.</p>

        <form method="POST" action="{{ route('running.rsvp.store', $event) }}">
            @csrf
            <label class="rc-label" for="extras">Bringing anyone?</label>
            <span class="rc-hint" id="extras-hint">Add extra runners. No names needed.</span>
            @include('running.partials.stepper', ['value' => 0])
            @error('extras') <p class="rc-error">{{ $message }}</p> @enderror
            <button type="submit" class="rc-btn rc-btn-primary rc-btn-block">RSVP</button>
        </form>

    @else
        <p>RSVPs for this {{ $noun }} have closed.</p>
    @endif
</section>
