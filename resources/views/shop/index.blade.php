@extends('layouts.app')

@section('title', 'Menu — Live Cafe')

@push('styles')
<style>
    .page-header {
        background: var(--green);
        padding: 3.5rem 2rem 3rem;
    }

    .page-header-inner {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header h1 {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 3.5vw, 2.8rem);
        color: var(--white);
        margin-bottom: 0.4rem;
    }

    .page-header p {
        color: rgba(255,255,255,0.7);
        font-size: 0.95rem;
    }

    .shop-body {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 2rem;
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 3rem;
        align-items: start;
    }

    /* Category sidebar */
    .category-nav { position: sticky; top: calc(var(--nav-h) + 1.5rem); }

    .category-nav h3 {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--slate);
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
    }

    .category-nav ul { list-style: none; }

    .category-nav li + li { margin-top: 2px; }

    .category-nav a {
        display: block;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        color: var(--slate);
        text-decoration: none;
        border-left: 2px solid transparent;
        transition: color 0.12s, border-color 0.12s;
    }

    .category-nav a:hover,
    .category-nav a.active {
        color: var(--green);
        border-left-color: var(--accent);
        background: var(--sage);
    }

    /* Product grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 1px;
        background: #ddd;
    }

    .product-card {
        background: var(--white);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        transition: background 0.15s;
    }

    .product-card:hover { background: var(--base); }

    .product-card-cat {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--slate);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.4rem;
    }

    .product-card h3 {
        font-family: var(--font-display);
        font-size: 1.05rem;
        color: var(--green);
        margin-bottom: 0.4rem;
        flex: 1;
    }

    .product-card-desc {
        font-size: 0.82rem;
        color: var(--slate);
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .product-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
        margin-top: auto;
    }

    .product-card-price {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--ink);
    }

    .btn-add {
        background: var(--green);
        color: var(--white);
        border: none;
        padding: 0.4rem 0.9rem;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        border-radius: 3px;
        font-family: var(--font-body);
        text-decoration: none;
        transition: background 0.15s;
    }

    .btn-add:hover { background: var(--green-light); }

    .badge-unavailable {
        font-size: 0.72rem;
        background: #f5f5f5;
        color: var(--slate);
        padding: 0.25rem 0.6rem;
        border-radius: 3px;
    }

    .section-heading {
        font-family: var(--font-display);
        font-size: 1.3rem;
        color: var(--green);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1.5px solid var(--sage);
    }

    .category-section + .category-section { margin-top: 3rem; }

    @media (max-width: 768px) {
        .shop-body { grid-template-columns: 1fr; }
        .category-nav { position: static; }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-inner">
        <h1>The menu</h1>
        <p>Order ahead and collect when you're ready.</p>
    </div>
</div>

<div class="shop-body">

    {{-- Category sidebar --}}
    <aside class="category-nav">
        <h3>Categories</h3>
        <ul>
            <li><a href="{{ route('shop.index') }}"
                class="{{ !request('category') ? 'active' : '' }}">All items</a></li>

            @foreach($categories as $category)
                <li>
                    <a href="{{ route('shop.index', ['category' => $category->id]) }}"
                       class="{{ request('category') == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </aside>

    {{-- Products --}}
    <div>
        @foreach($productsByCategory as $categoryName => $products)
            <div class="category-section">
                <h2 class="section-heading">{{ $categoryName }}</h2>
                <div class="product-grid">
                    @foreach($products as $product)
                        <div class="product-card">
                            <p class="product-card-cat">{{ $product->category->name }}</p>
                            <h3>{{ $product->name }}</h3>
                            @if($product->description)
                                <p class="product-card-desc">{{ Str::limit($product->description, 80) }}</p>
                            @endif
                            <div class="product-card-footer">
                                <span class="product-card-price">R{{ number_format($product->price, 2) }}</span>
                                @if($product->prod_availability && $product->quantity > 0)
                                    <form method="POST" action="{{ route('shop.cart.add', $product) }}">
                                        @csrf
                                        <button type="submit" class="btn-add">Add to cart</button>
                                    </form>
                                @else
                                    <span class="badge-unavailable">Unavailable</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        @if($productsByCategory->isEmpty())
            <p style="color:var(--slate);font-size:0.95rem;padding:2rem 0;">Nothing available in this category right now.</p>
        @endif
    </div>

</div>

@endsection