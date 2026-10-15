@extends('layouts.app')

@section('title', 'Shop — Live Cafe')

@section('content')
<div class="container shop-theme" style="max-width: 1200px; margin: 2rem auto; padding: 0 2rem;">

    <!-- Hero / Header Section -->
    <header style="margin-bottom: 3rem; text-align: center;">

<h1 class="layered-heading">
    <span class="pink-letter">E</span><span class="green-letters">AT.</span><span class="pink-letter">D</span><span class="green-letters">RINK.</span><span class="pink-letter">L</span><span class="green-letters">IVE</span>
</h1>


    </header>

    @if (session('error'))
        <div class="shop-flash">{{ session('error') }}</div>
    @endif

    <!-- Quick Category Buttons -->
    <nav class="cat-nav" aria-label="Product categories">
        @foreach($categories as $category)
            <a href="#category-{{ Str::slug($category->name) }}" class="cat-pill">
                <span class="cat-pill-clip">
                    <span class="cat-pill-text" data-text="{{ $category->name }}">{{ $category->name }}</span>
                </span>
            </a>
        @endforeach
    </nav>

    <!-- Products Loop Grouped by Category -->
    @forelse($productsByCategory as $categoryName => $products)
        <section id="category-{{ Str::slug($categoryName) }}" style="margin-bottom: 4rem; scroll-margin-top: 100px;">

            <!-- Existing animated category heading -->
            <div class="section-head">
                <div class="section-head-clip">
                    <h2 class="section-title">{{ $categoryName }}</h2>
                </div>
                <span class="section-line"></span>
            </div>

            {{-- Merch displays a coming-soon message instead of product cards --}}
            @if (str_contains(strtolower($categoryName), 'merch'))
                <div class="merch-coming-soon">
                    <span class="merch-coming-soon-label">LIVE RUN CLUB</span>
                    <h3>LIVE run-club merch coming soon</h3>
                    <p>Something special is on the way.</p>
                </div>

            @else
                @php
                    $isVideo = fn ($media) => (bool) preg_match('/\.(mp4|webm)$/i', $media->path);

                    $prototypeProducts = $products->filter(function ($product) use ($isVideo) {
                        return $product->images->contains(function ($media) use ($isVideo) {
                            return ! $isVideo($media)
                                && file_exists(public_path($media->path));
                        });
                    })->take(2);
                @endphp

                @if ($prototypeProducts->isNotEmpty())
                    <div class="product-grid">
                        @foreach ($prototypeProducts as $product)
                            @php
                                $soldOut = ! $product->prod_availability || $product->quantity < 1;

                                $photo = $product->images
                                    ->sortBy('sort_order')
                                    ->first(function ($media) use ($isVideo) {
                                        return ! $isVideo($media)
                                            && file_exists(public_path($media->path));
                                    });
                            @endphp

                            <article class="product-card {{ $soldOut ? 'is-sold-out' : '' }}">
                                @if ($soldOut)
                                    <div class="product-media">
                                        <img
                                            class="product-media-img"
                                            src="{{ asset($photo->path) }}"
                                            alt="{{ $photo->alt_text ?? $product->name }}"
                                            loading="lazy"
                                        >
                                        <span class="out-badge">Out of stock</span>
                                    </div>
                                @else
                                    <a
                                        class="product-media"
                                        href="{{ route('shop.product.show', $product->id) }}"
                                        aria-label="View details for {{ $product->name }}"
                                    >
                                        <img
                                            class="product-media-img"
                                            src="{{ asset($photo->path) }}"
                                            alt="{{ $photo->alt_text ?? $product->name }}"
                                            loading="lazy"
                                        >
                                    </a>
                                @endif

                                <div class="product-info">
                                    <h3>{{ $product->name }}</h3>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            @endif

        </section>
    @empty
        <div style="text-align: center; padding: 4rem 2rem; background: var(--white); border-radius: 8px; border: 1px solid var(--sage);">
            <p style="color: var(--slate); font-size: 1.1rem;">No items are currently available in the shop.</p>
        </div>
    @endforelse

</div>

<script>
(function () {

    /* ---- 1. Category headings animate as they enter or leave the viewport ---- */
    var heads = document.querySelectorAll('.section-head');

    if ('IntersectionObserver' in window) {
        // The trigger line sits 20% above the bottom of the screen.
        var headObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                // Headings already scrolled past the top stay visible.
                var scrolledPast = entry.rootBounds && entry.boundingClientRect.top < entry.rootBounds.top;

                if (entry.isIntersecting || scrolledPast) {
                    entry.target.classList.add('is-visible');
                } else {
                    entry.target.classList.remove('is-visible');
                }
            });
        }, { rootMargin: '0px 0px -20% 0px', threshold: 0 });

        heads.forEach(function (h) { headObserver.observe(h); });
    } else {
        heads.forEach(function (h) { h.classList.add('is-visible'); });
    }

    /* ---- 2. Live clips: play on hover (mouse) or once in view (touch) ---- */
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    var canHover = window.matchMedia('(hover: hover)').matches;

    document.querySelectorAll('.product-media.has-clip').forEach(function (media) {
        var video = media.querySelector('video');
        if (!video) { return; }

        function play() {
            video.currentTime = 0;
            media.classList.add('is-playing');

            var p = video.play();
            if (p && p.catch) { p.catch(function () {}); }
        }

        function stop() {
            media.classList.remove('is-playing');
            video.pause();
        }

        if (canHover) {
            media.addEventListener('mouseenter', play);
            media.addEventListener('mouseleave', stop);
            media.addEventListener('focus', play);
            media.addEventListener('blur', stop);
        } else if ('IntersectionObserver' in window) {
            var clipObserver = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        play();
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.7 });

            clipObserver.observe(media);
        }
    });

})();
</script>
@endsection

@push('styles')
<style>
    /* ---------- Page colours, taken from the Live Cafe menu ---------- */
    body:has(.shop-theme) { background: #F8E8F0; }   /* menu pink */

    .shop-theme {
        --menu-sage: #D0D4B4;    /* exact green of the "Food" lettering on the menu */
        --line-green: #78b24b;   /* deeper green for the animated line */
        --slate: #4B4450;        /* darker grey for readable descriptions */
        --green-light: #5B3A4E;  /* darker page-title accent */
    }

    .shop-flash {
        padding: 0.85rem 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
        text-align: center;
        font-size: 0.9rem;
        background: #fdecea;
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    /* ---------- Merch coming soon ---------- */
    .merch-coming-soon {
        padding: 3.5rem 1.5rem;
        margin-top: 1.5rem;
        text-align: center;
        background: var(--white);
        border: 1px solid var(--sage);
        border-radius: 8px;
    }

    .merch-coming-soon-label {
        display: inline-block;
        margin-bottom: 1rem;
        color: var(--green-light);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.18em;
    }

    .merch-coming-soon h3 {
        margin: 0 0 0.75rem;
        color: var(--green-light);
        font-family: var(--font-display);
        font-size: clamp(1.4rem, 3vw, 2rem);
    }

    .merch-coming-soon p {
        margin: 0;
        color: var(--slate);
        font-size: 0.95rem;
    }

    /* ---------- Category buttons ---------- */
    .cat-nav {
        display: flex;
        gap: 1rem;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 3rem;
        width: 100%;
    }

    .cat-pill {
        position: relative;
        isolation: isolate;
        display: inline-flex;
        overflow: hidden;
        padding: 0.6rem 1.5rem;
        background: var(--white);
        color: var(--ink);
        border: 1px solid var(--sage);
        border-radius: 999px;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    /* Touch screens get a small press effect instead of hover */
    .cat-pill:active { transform: scale(0.97); }

    .cat-pill-clip {
        display: block;
        height: 1.4em;
        line-height: 1.4;
        overflow: hidden;
    }

    .cat-pill-text { display: block; position: relative; }

    /* Hover effects only on devices that can really hover */
    @media (hover: hover) {
        .cat-pill::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--accent);
            transform: translateY(101%);
            transition: transform 0.35s ease;
            z-index: -1;
        }

        .cat-pill-text { transition: transform 0.35s ease; }

        .cat-pill-text::after {
            content: attr(data-text);
            position: absolute;
            left: 0;
            top: 100%;
            color: var(--white);
        }

        .cat-pill:hover,
        .cat-pill:focus-visible {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        }

        .cat-pill:hover::before,
        .cat-pill:focus-visible::before { transform: translateY(0); }

        
/* Alternate the category hover colours: green, pink, green, pink */
.cat-nav > .cat-pill:nth-child(4n + 1)::before,
.cat-nav > .cat-pill:nth-child(4n + 3)::before {
    background: #2ab404;
}

.cat-nav > .cat-pill:nth-child(4n + 2)::before,
.cat-nav > .cat-pill:nth-child(4n + 4)::before {
    background: #c76f8b;
}


        .cat-pill:hover .cat-pill-text,
        .cat-pill:focus-visible .cat-pill-text { transform: translateY(-100%); }
    }

    /* ---------- Category headings ---------- */
    .section-head { margin-bottom: 1.5rem; }

    .section-head-clip {
        overflow: hidden;
        padding: 0.15em 0 0.2em;
    }

    /* Hidden state: gentle and a little quicker when scrolling back down */
    .section-title {
        margin: 0;
        font-family: var(--font-display);
        font-size: 1.75rem;
        line-height: 1.25;
        color: var(--ink);
        transform: translateY(130%);
        transition: transform 0.7s ease-in-out;
    }

    .section-line {
        display: block;
        height: 2px;
        background: var(--line-green, var(--sage));
        transform: scaleX(0);
        transform-origin: left center;
        transition: transform 1s ease-in-out;
    }

    /* Visible state: the word rises and the line draws across */
    .section-head.is-visible .section-title {
        transform: translateY(0);
        transition: transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .section-head.is-visible .section-line {
        transform: scaleX(1);
        transition: transform 1.8s cubic-bezier(0.45, 0, 0.25, 1);
    }

    /* ---------- Product cards ---------- */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
    }

    .product-card {
        position: relative;
        display: flex;
        flex-direction: column;
        background: var(--white);
        border: 1px solid var(--sage);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    @media (hover: hover) {
        .product-card:not(.is-sold-out):hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.05);
        }
    }

    .product-media {
        position: relative;
        display: block;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: var(--sage);
    }

    a.product-media { cursor: pointer; }

    .product-media-img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 2s ease-out;
    }

    .product-media-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--green);
        font-family: var(--font-display);
    }

    /* Photos with no clip get a slow, subtle zoom on hover */
    @media (hover: hover) {
        a.product-media:not(.has-clip):hover .product-media-img {
            transform: scale(1.08);
        }
    }

    /* The clip sits on top of the photo and fades in when it plays */
    .product-media-clip {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .product-media.is-playing .product-media-clip { opacity: 1; }

    /* Out of stock: blurred picture and a badge */
    .product-card.is-sold-out .product-media-img,
    .product-card.is-sold-out .product-media-placeholder {
        filter: blur(6px);
        transform: scale(1.1);
    }

    .out-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: var(--danger);
        color: var(--white);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        padding: 0.4rem 0.9rem;
        border-radius: 3px;
        white-space: nowrap;
    }

   
.product-info {
    display: flex;
    flex-direction: column;
    padding: 1rem 1.25rem;
}

.product-info h3 {
    font-size: 1.05rem;
    color: var(--ink);
    font-weight: 600;
    margin: 0;
    text-align: center;
}

    .product-info p {
        color: var(--slate);
        font-size: 0.875rem;
        line-height: 1.4;
        margin-bottom: 1rem;
    }

    .product-price {
        margin-top: auto;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ink);
    }

    .is-sold-out .product-price { color: var(--slate); }

    /* Respect reduced-motion settings */
    @media (prefers-reduced-motion: reduce) {
        .cat-pill, .cat-pill::before, .cat-pill-text,
        .section-title, .section-line,
        .product-card, .product-media-img, .product-media-clip {
            transition: none;
        }

        .section-title, .section-line { transform: none; }
    }

  
/* Layered print effect for the main shop heading */
.layered-heading {
    font-family: var(--font-display);
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 700;
    line-height: 1.15;
    margin-bottom: 0.5rem;
    text-shadow:
        2px 2px 0 #c76f8b,
        4px 4px 0 #ad5875;
}

.pink-letter {
    color: #c76f8b;
}

.green-letters {
    color: #2ab404;
}


.layered-heading::before {
    content: attr(data-text);
    position: absolute;
    inset: 0;
    color: #e8a5ba;
    transform: translate(-2px, -2px);
    z-index: -1;
    opacity: 0.65;
    pointer-events: none;
}

</style>

<noscript>
    <style>
        .section-title, .section-line { transform: none; }
    </style>
</noscript>
@endpush