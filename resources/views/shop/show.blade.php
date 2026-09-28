@extends('layouts.app')
@section('title', $product->name . ' — Live Cafe')
@section('content')
<div style="max-width:1200px;margin:3rem auto;padding:0 2rem;">
    <a href="{{ route('shop.index') }}" style="font-size:0.875rem;color:var(--slate);text-decoration:none;">← Back to menu</a>
    <h1 style="font-family:var(--font-display);font-size:2rem;color:var(--green);margin:1.5rem 0 0.5rem;">{{ $product->name }}</h1>
    <p style="color:var(--slate);margin-bottom:0.5rem;">{{ $product->category->name }}</p>
    <p style="font-size:1.2rem;font-weight:600;margin-bottom:1rem;">R{{ number_format($product->price, 2) }}</p>
    @if($product->description)
        <p style="color:var(--slate);max-width:52ch;line-height:1.7;">{{ $product->description }}</p>
    @endif
    <p style="margin-top:2rem;color:var(--slate);font-size:0.875rem;">Full product page coming soon.</p>
</div>
@endsection