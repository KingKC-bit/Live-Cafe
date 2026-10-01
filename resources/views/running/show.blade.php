@extends('layouts.app')

@php
    $optionalFacts = collect([
        'Distance' => $event->distanceLabel(),
        'Pace' => $event->pace,
        'Dress code' => $event->dress_code,
    ])->filter();
@endphp

@section('title', $event->title.' — Live Running Club')

@push('styles')
    @include('running.partials.styles')
@endpush

@section('content')

<div class="rc-wrap rc-detail">
    <a href="{{ route('running.index') }}" class="rc-back">&larr; All runs</a>

    <div class="rc-detail-grid">
        <div class="rc-detail-main">
            <div class="rc-badges">
                <span class="rc-badge rc-badge-type">{{ $event->typeLabel() }}</span>
                @if ($event->sponsor)
                    <span class="rc-badge rc-badge-sponsor">Presented by {{ $event->sponsor }}</span>
                @endif
                @if ($event->isCancelled())
                    <span class="rc-badge rc-badge-cancelled">Cancelled</span>
                @endif
            </div>

            <h1 class="rc-detail-title">{{ $event->title }}</h1>
            <p class="rc-detail-when">{{ $event->event_date->format('l j F Y') }} · {{ $event->startTimeLabel() }}</p>

            <img class="rc-detail-photo"
                 src="{{ $event->photoUrl() }}"
                 alt="{{ $event->photo?->alt_text ?: 'Live Running Club members on a club run' }}">

            <dl class="rc-facts">
                <div>
                    <dt>Date</dt>
                    <dd>{{ $event->event_date->format('l j F Y') }}</dd>
                </div>
                <div>
                    <dt>Start time</dt>
                    <dd>{{ $event->startTimeLabel() }}</dd>
                </div>
                <div class="rc-fact-wide">
                    <dt>Location</dt>
                    <dd>
                        {{ $event->address }}<br>
                        <a href="{{ $event->mapUrl() }}" class="rc-map-link" target="_blank" rel="noopener">Open in Google Maps</a>
                    </dd>
                </div>
                @foreach ($optionalFacts as $label => $value)
                    {{-- With an odd number of facts the last one fills the row, so no empty cell shows. --}}
                    <div @class(['rc-fact-wide' => $loop->last && $loop->count % 2 === 1])>
                        <dt>{{ $label }}</dt>
                        <dd>{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>

            @if ($event->description !== '')
                <h2 class="rc-subtitle">About this {{ $event->isRun() ? 'run' : 'event' }}</h2>
                <p class="rc-prose">{!! nl2br(e($event->description)) !!}</p>
            @endif
        </div>

        <aside class="rc-detail-side">
            @include('running.partials.rsvp-card', ['event' => $event, 'myRsvp' => $myRsvp])
        </aside>
    </div>
</div>

@endsection

@push('scripts')
    @include('running.partials.stepper-script')
@endpush
