@extends('layouts.app')
@section('title', 'Event — Live Cafe Running Club')
@section('content')
<div style="max-width:1200px;margin:3rem auto;padding:0 2rem;">
    <a href="{{ route('running.index') }}" style="font-size:0.875rem;color:var(--slate);text-decoration:none;">← Back to events</a>
    <h1 style="font-family:var(--font-display);font-size:2rem;color:var(--green);margin:1.5rem 0 0.5rem;">
        {{ $event->description ?? 'Saturday Run' }}
    </h1>
    <p style="color:var(--slate);margin-bottom:0.5rem;">
        {{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}
        at {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}
    </p>
    <p style="color:var(--slate);margin-bottom:2rem;">{{ $event->address }}</p>
    <p style="color:var(--slate);font-size:0.875rem;">RSVP functionality coming soon.</p>
</div>
@endsection