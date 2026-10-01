@extends('layouts.app')

@section('title', 'My RSVPs — Live Running Club')

@push('styles')
    @include('running.partials.styles')
@endpush

@section('content')

<div class="rc-wrap">
    <a href="{{ route('running.index') }}" class="rc-back">&larr; Runs and events</a>

    <header class="rc-page-head">
        <p class="rc-eyebrow">Live Running Club</p>
        <h1 class="rc-page-title">My RSVPs</h1>
    </header>

    @forelse ($rsvps as $rsvp)
        @include('running.partials.event-row', ['event' => $rsvp->event, 'myRsvp' => $rsvp])
    @empty
        <p class="rc-empty">
            You haven't RSVP'd to anything coming up yet.
            <a href="{{ route('running.index') }}" class="rc-link">See upcoming runs and events</a>
        </p>
    @endforelse
</div>

@endsection
