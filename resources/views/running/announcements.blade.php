@extends('layouts.app')
@section('title', 'Announcements — Live Cafe')
@section('content')
<div style="max-width:1200px;margin:3rem auto;padding:0 2rem;">
    <h1 style="font-family:var(--font-display);font-size:2rem;color:var(--green);margin-bottom:2rem;">Announcements</h1>
    @forelse($announcements as $announcement)
        <div style="background:var(--white);border-left:4px solid var(--mauve);padding:1.25rem 1.5rem;margin-bottom:1px;">
            <p style="font-size:0.75rem;color:#aaa;margin-bottom:0.4rem;">{{ $announcement->created_at->diffForHumans() }}</p>
            <p style="color:var(--slate);line-height:1.65;">{{ $announcement->description }}</p>
        </div>
    @empty
        <p style="color:var(--slate);">No announcements yet.</p>
    @endforelse
</div>
@endsection