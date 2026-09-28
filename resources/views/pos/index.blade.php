@extends('layouts.app')

@section('title', 'POS — Live Cafe')

@push('styles')
<style>
    .pos-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2.5rem 2rem;
    }

    .pos-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pos-header h1 {
        font-family: var(--font-display);
        font-size: 1.8rem;
        color: var(--green);
    }

    .pos-header p {
        font-size: 0.875rem;
        color: var(--slate);
        margin-top: 0.2rem;
    }

    .pos-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 2rem;
        align-items: start;
    }

    /* ── Product panel ── */
    .pos-products h2 {
        font-family: var(--font-display);
        font-size: 1.1rem;
        color: var(--green);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1.5px solid var(--sage);
    }

    .pos-product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1px;
        background: #ddd;
    }

    .pos-product-btn {
        background: var(--white);
        border: none;
        padding: 1.25rem 1rem;
        text-align: left;
        cursor: pointer;
        transition: background 0.12s;
        font-family: var(--font-body);
    }

    .pos-product-btn:hover { background: var(--base); }

    .pos-product-btn .name {
        font-family: var(--font-display);
        font-size: 0.95rem;
        color: var(--green);
        display: block;
        margin-bottom: 0.3rem;
    }

    .pos-product-btn .price {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--ink);
    }

    .pos-product-btn .qty {
        font-size: 0.72rem;
        color: var(--slate);
        margin-top: 0.2rem;
        display: block;
    }

    /* ── Cart panel ── */
    .pos-cart {
        background: var(--white);
        border-top: 3px solid var(--green);
        padding: 1.5rem;
        position: sticky;
        top: calc(var(--nav-h) + 1.5rem);
    }

    .pos-cart h2 {
        font-family: var(--font-display);
        font-size: 1.1rem;
        color: var(--green);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1.5px solid var(--sage);
    }

    .cart-empty {
        font-size: 0.875rem;
        color: var(--slate);
        text-align: center;
        padding: 2rem 0;
    }

    .cart-items { margin-bottom: 1rem; }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.875rem;
        gap: 0.5rem;
    }

    .cart-item-name { color: var(--ink); flex: 1; }
    .cart-item-price { font-weight: 600; color: var(--ink); white-space: nowrap; }

    .cart-item-remove {
        background: none;
        border: none;
        color: var(--danger);
        cursor: pointer;
        font-size: 0.8rem;
        padding: 0;
        font-family: var(--font-body);
    }

    .cart-total {
        display: flex;
        justify-content: space-between;
        font-weight: 600;
        font-size: 1rem;
        padding: 0.75rem 0;
        border-top: 2px solid var(--ink);
        margin-bottom: 1rem;
    }

    .payment-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--slate);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.5rem;
    }

    .payment-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .payment-option {
        border: 1.5px solid var(--sage);
        background: none;
        padding: 0.6rem;
        font-size: 0.875rem;
        font-weight: 600;
        font-family: var(--font-body);
        cursor: pointer;
        transition: all 0.12s;
        color: var(--slate);
    }

    .payment-option.selected,
    .payment-option:hover {
        border-color: var(--green);
        background: var(--green);
        color: var(--white);
    }

    .btn-charge {
        width: 100%;
        background: var(--accent);
        color: var(--white);
        border: none;
        padding: 0.85rem;
        font-size: 1rem;
        font-weight: 600;
        font-family: var(--font-body);
        cursor: pointer;
        transition: opacity 0.15s;
    }

    .btn-charge:hover { opacity: 0.88; }
    .btn-charge:disabled { opacity: 0.4; cursor: not-allowed; }

    .btn-clear {
        width: 100%;
        background: none;
        border: 1.5px solid var(--sage);
        color: var(--slate);
        padding: 0.6rem;
        font-size: 0.875rem;
        font-family: var(--font-body);
        cursor: pointer;
        margin-top: 0.5rem;
        transition: border-color 0.12s;
    }

    .btn-clear:hover { border-color: var(--danger); color: var(--danger); }

    @media (max-width: 768px) {
        .pos-grid { grid-template-columns: 1fr; }
        .pos-cart { position: static; }
    }
</style>
@endpush

@section('content')
<div class="pos-wrap">
    <div class="pos-header">
        <div>
            <h1>Point of Sale</h1>
            <p>{{ auth()->user()->name }} {{ auth()->user()->surname }} &mdash; {{ now()->format('l, d F Y') }}</p>
        </div>
    </div>

    <div class="pos-grid">

        {{-- Product selection panel --}}
        <div class="pos-products">
            <h2>Select products</h2>
            <div class="pos-product-grid" id="productGrid">
                @forelse($products as $product)
                    <button
                        class="pos-product-btn"
                        onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->quantity }})"
                    >
                        <span class="name">{{ $product->name }}</span>
                        <span class="price">R{{ number_format($product->price, 2) }}</span>
                        <span class="qty">{{ $product->quantity }} in stock</span>
                    </button>
                @empty
                    <p style="padding:1.5rem;color:var(--slate);font-size:0.9rem;">No products available.</p>
                @endforelse
            </div>
        </div>

        {{-- Cart panel --}}
        <div class="pos-cart">
            <h2>Current sale</h2>

            <div id="cartEmpty" class="cart-empty">No items added yet.</div>

            <div id="cartItems" class="cart-items" style="display:none;"></div>

            <div id="cartTotal" class="cart-total" style="display:none;">
                <span>Total</span>
                <span id="totalAmount">R0.00</span>
            </div>

            <div id="cartControls" style="display:none;">
                <p class="payment-label">Payment method</p>
                <div class="payment-options">
                    <button class="payment-option selected" id="payCash" onclick="selectPayment('cash')">Cash</button>
                    <button class="payment-option" id="payCard" onclick="selectPayment('card')">Card</button>
                </div>

                <form method="POST" action="{{ route('pos.sale.store') }}" id="saleForm">
                    @csrf
                    <input type="hidden" name="payment_method" id="paymentMethod" value="cash">
                    <input type="hidden" name="items" id="itemsInput">
                    <button type="submit" class="btn-charge" id="chargeBtn">
                        Charge
                    </button>
                </form>

                <button class="btn-clear" onclick="clearCart()">Clear sale</button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    let cart = {};

    function addToCart(id, name, price, stock) {
        if (cart[id]) {
            if (cart[id].qty >= stock) {
                alert('No more stock available for ' + name);
                return;
            }
            cart[id].qty++;
        } else {
            cart[id] = { id, name, price, qty: 1, stock };
        }
        renderCart();
    }

    function removeFromCart(id) {
        delete cart[id];
        renderCart();
    }

    function clearCart() {
        cart = {};
        renderCart();
    }

    function selectPayment(method) {
        document.getElementById('paymentMethod').value = method;
        document.getElementById('payCash').classList.toggle('selected', method === 'cash');
        document.getElementById('payCard').classList.toggle('selected', method === 'card');
    }

    function renderCart() {
        const ids = Object.keys(cart);
        const empty = document.getElementById('cartEmpty');
        const items = document.getElementById('cartItems');
        const total = document.getElementById('cartTotal');
        const controls = document.getElementById('cartControls');

        if (ids.length === 0) {
            empty.style.display = 'block';
            items.style.display = 'none';
            total.style.display = 'none';
            controls.style.display = 'none';
            return;
        }

        empty.style.display = 'none';
        items.style.display = 'block';
        total.style.display = 'flex';
        controls.style.display = 'block';

        let html = '';
        let sum = 0;

        ids.forEach(id => {
            const item = cart[id];
            const lineTotal = item.price * item.qty;
            sum += lineTotal;
            html += `
                <div class="cart-item">
                    <span class="cart-item-name">${item.qty}× ${item.name}</span>
                    <span class="cart-item-price">R${lineTotal.toFixed(2)}</span>
                    <button class="cart-item-remove" onclick="removeFromCart(${id})">✕</button>
                </div>`;
        });

        items.innerHTML = html;
        document.getElementById('totalAmount').textContent = 'R' + sum.toFixed(2);
        document.getElementById('chargeBtn').textContent = 'Charge R' + sum.toFixed(2);
        document.getElementById('itemsInput').value = JSON.stringify(
            ids.map(id => ({ product_id: parseInt(id), quantity: cart[id].qty }))
        );
    }
</script>
@endpush