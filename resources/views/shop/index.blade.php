@extends('layouts.app')
@section('title', 'POS — Live Cafe')
@section('content')
<div style="max-width:1200px;margin:3rem auto;padding:0 2rem;">
    <h1 style="font-family:var(--font-display);font-size:2rem;color:var(--green);margin-bottom:0.5rem;">Point of Sale</h1>
    <p style="color:var(--slate);margin-bottom:2rem;">Staff terminal — {{ auth()->user()->name }}</p>
    <p style="color:var(--slate);font-size:0.875rem;">POS functionality coming soon.</p>
</div>
@endsection