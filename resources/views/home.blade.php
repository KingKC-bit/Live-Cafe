@extends('layouts.app')

@section('title', 'Live Cafe — Sandton')

@push('styles')
<style>
    /* ── Hero ── */
    .hero {
        background: var(--green);
        color: var(--white);
        padding: 6rem 2rem 5rem;
        position: relative;
        overflow: hidden;
    }

    .hero::after {
        content: '';
        position: absolute;
        right: -80px;
        top: -80px;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: rgba(232, 160, 32, 0.08);
        pointer-events: none;
    }

    .hero-inner {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .hero-eyebrow {
        display: inline-block;
        background: var(--accent);
        color: var(--ink);
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.3rem 0.75rem;
        margin-bottom: 1.5rem;
        letter-spacing: 0.02em;
    }

    .hero h1 {
        font-family: var(--font-display);
        font-size: clamp(2.4rem, 5vw, 4rem);
        line-height: 1.1;
        max-width: 14ch;
        margin-bottom: 1.25rem;
        font-weight: 700;
    }

    .hero p {
        color: rgba(255,255,255,0.75);
        font-size: 1.05rem;
        max-width: 46ch;
        line-height: 1.65;
        margin-bottom: 2.5rem;
    }

    .hero-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: var(--accent);
        color: var(--ink);
        padding: 0.8rem 1.75rem;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        border-radius: 4px;
        transition: opacity 0.15s;
        font-family: var(--font-body);
    }

    .btn-primary:hover { opacity: 0.88; }

    .btn-outline {
        border: 1.5px solid rgba(255,255,255,0.45);
        color: var(--white);
        padding: 0.8rem 1.75rem;
        font-weight: 500;
        font-size: 0.95rem;
        text-decoration: none;
        border-radius: 4px;
        transition: border-color 0.15s, background 0.15s;
        font-family: var(--font-body);
    }

    .btn-outline:hover {
        border-color: var(--white);
        background: rgba(255,255,255,0.07);
    }

    /* ── Section shared ── */
    .section { padding: 5rem 2rem; }
    .section-alt { background: var(--sage); }

    .section-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--green);
        letter-spacing: 0.04em;
        margin-bottom: 0.6rem;
        text-transform: uppercase;
    }

    .section h2 {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3vw, 2.4rem);
        color: var(--green);
        max-width: 22ch;
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    .section-lead {
        color: var(--slate);
        font-size: 1rem;
        max-width: 52ch;
        line-height: 1.7;
        margin-bottom: 2rem;
    }

    /* ── Feature cards ── */
    .feature-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .feature-card {
        background: var(--white);
        border-top: 3px solid var(--green);
        padding: 2rem;
    }

    .feature-card-icon {
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }

    .feature-card h3 {
        font-family: var(--font-display);
        font-size: 1.2rem;
        color: var(--green);
        margin-bottom: 0.6rem;
    }

    .feature-card p {
        color: var(--slate);
        font-size: 0.9rem;
        line-height: 1.65;
        margin-bottom: 1.25rem;
    }

    .feature-card a {
        color: var(--green);
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
        padding-bottom: 1px;
        transition: color 0.15s;
    }

    .feature-card a:hover { color: var(--accent); }

    /* ── Shop strip ── */
    .shop-strip {
        max-width: 1200px;
        margin: 0 auto;
    }

    .shop-strip-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .shop-strip a.see-all {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--green);
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
        padding-bottom: 2px;
    }

    .product-row {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }

    .product-tile {
        background: var(--white);
        padding: 1.5rem;
        border-bottom: 3px solid transparent;
        transition: border-color 0.15s;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .product-tile:hover { border-color: var(--accent); }

    .product-tile-cat {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--slate);
        letter-spacing: 0.03em;
        text-transform: uppercase;
        margin-bottom: 0.4rem;
    }

    .product-tile h4 {
        font-family: var(--font-display);
        font-size: 1rem;
        color: var(--green);
        margin-bottom: 0.5rem;
    }

    .product-tile-price {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--ink);
    }

    /* ── Running strip ── */
    .running-strip {
        max-width: 1200px;
        margin: 0 auto;
    }

    .running-strip-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .running-strip a.see-all {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--green);
        text-decoration: none;
        border-bottom: 1.5px solid var(--accent);
        padding-bottom: 2px;
    }

    .event-row {
        display: flex;
        flex-direction: column;
        gap: 1px;
        background: #ddd;
    }

    .event-item {
        background: var(--white);
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .event-date {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--accent);
        min-width: 90px;
    }

    .event-name {
        font-family: var(--font-display);
        font-size: 1rem;
        color: var(--green);
        flex: 1;
    }

    .event-location {
        font-size: 0.82rem;
        color: var(--slate);
    }

    .event-rsvp {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--green);
        text-decoration: none;
        border: 1.5px solid var(--green);
        padding: 0.3rem 0.85rem;
        border-radius: 3px;
        white-space: nowrap;
        transition: background 0.15s, color 0.15s;
    }

    .event-rsvp:hover {
        background: var(--green);
        color: var(--white);
    }

    /* ── CTA band ── */
    .cta-band {
        background: var(--accent);
        padding: 4rem 2rem;
        text-align: center;
    }

    .cta-band h2 {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        color: var(--ink);
        margin-bottom: 0.75rem;
    }

    .cta-band p {
        color: rgba(0,0,0,0.65);
        font-size: 1rem;
        max-width: 42ch;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }

    .btn-dark {
        background: var(--green);
        color: var(--white);
        padding: 0.8rem 2rem;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        border-radius: 4px;
        transition: opacity 0.15s;
        font-family: var(--font-body);
    }

    .btn-dark:hover { opacity: 0.88; }

    @media (max-width: 600px) {
        .hero { padding: 4rem 1.25rem 3.5rem; }
        .section { padding: 3.5rem 1.25rem; }
        .event-item { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="hero">
    <div class="hero-inner">
        <span class="hero-eyebrow">Sandton's running cafe</span>
        <h1>Coffee, food, and community miles.</h1>
        <p>Order ahead for collection, join the Saturday running club, and stay connected with everything happening at Live Cafe.</p>
        <div class="hero-actions">
            <a href="{{ route('shop.index') }}" class="btn-primary">Browse the menu</a>
            <a href="{{ route('running.index') }}" class="btn-outline">Running club</a>
        </div>
    </div>
</section>

{{-- What we offer --}}
<section class="section">
    <div class="container">
        <p class="section-label">What we offer</p>
        <h2>One place for everything Live Cafe.</h2>

        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-card-icon">☕</div>
                <h3>Order for collection</h3>
                <p>Browse our food, drinks, and merchandise. Place your order online and collect when it's ready — no queue, no wait.</p>
                <a href="{{ route('shop.index') }}">Browse the menu</a>
            </div>

            <div class="feature-card">
                <div class="feature-card-icon">🏃</div>
                <h3>Saturday running club</h3>
                <p>Weekly runs from the cafe every Saturday. RSVP to secure your spot, bring friends, and see who else is joining.</p>
                <a href="{{ route('running.index') }}">See upcoming runs</a>
            </div>

            <div class="feature-card">
                <div class="feature-card-icon">📣</div>
                <h3>Announcements</h3>
                <p>Stay up to date with what's happening at Live Cafe — new products, special events, and club news.</p>
                <a href="{{ route('running.announcements.index') }}">Read announcements</a>
            </div>
        </div>
    </div>
</section>

{{-- Shop strip --}}
<section class="section section-alt">
    <div class="shop-strip">
        <div class="shop-strip-header">
            <div>
                <p class="section-label">The menu</p>
                <h2>Fresh food and great coffee.</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="see-all">View full menu</a>
        </div>

        <div class="product-row">
            @forelse($featuredProducts as $product)
                <a href="{{ route('shop.product.show', $product) }}" class="product-tile">
                    <p class="product-tile-cat">{{ $product->category->name }}</p>
                    <h4>{{ $product->name }}</h4>
                    <span class="product-tile-price">R{{ number_format($product->price, 2) }}</span>
                </a>
            @empty
                <p style="color:var(--slate);font-size:0.9rem;">No products available right now.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- Running strip --}}
<section class="section">
    <div class="running-strip">
        <div class="running-strip-header">
            <div>
                <p class="section-label">Running club</p>
                <h2>Upcoming events.</h2>
            </div>
            <a href="{{ route('running.index') }}" class="see-all">All events</a>
        </div>

        <div class="event-row">
            @forelse($upcomingEvents as $event)
                <div class="event-item">
                    <span class="event-date">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('D, d M') }}
                        &middot;
                        {{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}
                    </span>
                    <span class="event-name">{{ $event->description ?? 'Saturday Run' }}</span>
                    <span class="event-location">{{ $event->address }}</span>
                    <a href="{{ route('running.events.show', $event) }}" class="event-rsvp">RSVP</a>
                </div>
            @empty
                <div class="event-item">
                    <span style="color:var(--slate);font-size:0.9rem;">No upcoming events — check back soon.</span>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA band --}}
@guest
<section class="cta-band">
    <h2>Join the Live Cafe community.</h2>
    <p>Create an account to place orders, RSVP to runs, and track everything in one place.</p>
    <a href="{{ route('register') }}" class="btn-dark">Create an account</a>
</section>
@endguest

@endsection