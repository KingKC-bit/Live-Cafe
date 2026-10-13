@extends('layouts.app')

@section('title', 'Sales & Orders — Live Cafe POS')

@push('styles')
<style>
    .sales-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2.5rem 2rem;
    }

    .sales-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .sales-header h1 {
        font-family: var(--font-display);
        font-size: 1.8rem;
        color: var(--green);
    }

    .back-btn {
        background: var(--green);
        color: var(--white);
        padding: 0.6rem 1.25rem;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 3px;
        transition: opacity 0.15s;
    }

    .back-btn:hover { opacity: 0.88; }

    /* ── Tabs ── */
    .tabs {
        display: flex;
        gap: 0;
        margin-bottom: 2rem;
        border-bottom: 2px solid var(--sage);
    }

    .tab-btn {
        background: none;
        border: none;
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: var(--font-body);
        color: var(--slate);
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: color 0.12s;
    }

    .tab-btn.active {
        color: var(--green);
        border-bottom-color: var(--green);
    }

    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* ── Section headings ── */
    .section-heading {
        font-family: var(--font-display);
        font-size: 1.1rem;
        color: var(--green);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1.5px solid var(--sage);
    }

    /* ── Table ── */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        background: var(--white);
    }

    .data-table th {
        background: var(--green);
        color: var(--white);
        padding: 0.7rem 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.78rem;
        letter-spacing: 0.02em;
    }

    .data-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f0f0f0;
        color: var(--slate);
        vertical-align: top;
    }

    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: var(--base); }

    /* ── Status badges ── */
    .badge {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.2rem 0.55rem;
        border-radius: 3px;
        text-transform: capitalize;
    }

    .badge-cash     { background: #e8f5e9; color: #2e7d32; }
    .badge-card     { background: #e3f2fd; color: #1565c0; }
    .badge-pending  { background: #fff8e6; color: #8a6200; }
    .badge-confirmed{ background: #e8f5e9; color: #2e7d32; }
    .badge-collected{ background: #f3e5f5; color: #6a1b9a; }
    .badge-cancelled{ background: #fce4e4; color: #b71c1c; }

    /* ── Order verify panel ── */
    .verify-box {
        background: var(--white);
        border-top: 3px solid var(--accent);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .verify-box h2 {
        font-family: var(--font-display);
        font-size: 1rem;
        color: var(--green);
        margin-bottom: 0.75rem;
    }

    .verify-form {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .verify-input {
        flex: 1;
        min-width: 200px;
        padding: 0.65rem 0.9rem;
        border: 1.5px solid var(--sage);
        font-size: 0.9rem;
        font-family: var(--font-body);
        outline: none;
    }

    .verify-input:focus { border-color: var(--green); }

    .verify-btn {
        background: var(--accent);
        color: var(--white);
        border: none;
        padding: 0.65rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: var(--font-body);
        cursor: pointer;
        transition: opacity 0.15s;
    }

    .verify-btn:hover { opacity: 0.88; }

    /* ── Order detail expand ── */
    .order-items {
        font-size: 0.8rem;
        color: var(--slate);
        line-height: 1.6;
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
        color: var(--slate);
        font-size: 0.9rem;
        background: var(--white);
    }

    @media (max-width: 768px) {
        .data-table { font-size: 0.8rem; }
        .data-table th, .data-table td { padding: 0.6rem 0.6rem; }
    }
</style>
@endpush

@section('content')
<div class="sales-wrap">

    <div class="sales-header">
        <h1>Sales &amp; Orders</h1>
        <a href="{{ route('pos.index') }}" class="back-btn">← Back to POS</a>
    </div>

    {{-- Order ID lookup --}}
    <div class="verify-box">
        <h2>Verify a collection order</h2>
        <p style="font-size:0.85rem;color:var(--slate);margin-bottom:1rem;">
            Enter the customer's order ID to verify and mark as collected.
        </p>
        <div class="verify-form">
            <input
                type="number"
                id="orderIdInput"
                class="verify-input"
                placeholder="Enter order ID e.g. 42"
                min="1"
            >
            <button class="verify-btn" onclick="lookupOrder()">Look up order</button>
        </div>
        <div id="orderResult" style="margin-top:1rem;display:none;"></div>
    </div>

    {{-- Tabs --}}
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('sales', this)">
            POS Sales ({{ $sales->count() }})
        </button>
        <button class="tab-btn" onclick="switchTab('orders', this)">
            Online Orders to Collect ({{ $pendingOrders->count() }})
        </button>
    </div>

    {{-- POS Sales tab --}}
    <div id="tab-sales" class="tab-panel active">
        <h2 class="section-heading">Recent in-person sales</h2>

        @if($sales->isEmpty())
            <div class="empty-state">No sales recorded yet today.</div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Staff</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->user->name }} {{ $sale->user->surname }}</td>
                            <td>
                                <div class="order-items">
                                    @foreach($sale->salesDetails as $detail)
                                        {{ $detail->quantity }}× {{ $detail->product->name }}
                                        <span style="color:#aaa;">(R{{ number_format($detail->unit_price, 2) }})</span><br>
                                    @endforeach
                                </div>
                            </td>
                            <td><strong>R{{ number_format($sale->total, 2) }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $sale->payment_method }}">
                                    {{ $sale->payment_method }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($sale->occurred_at)->format('H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Online Orders tab --}}
    <div id="tab-orders" class="tab-panel">
        <h2 class="section-heading">Online orders awaiting collection</h2>

        @if($pendingOrders->isEmpty())
            <div class="empty-state">No online orders pending collection.</div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Collect at</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingOrders as $order)
                        <tr id="order-row-{{ $order->id }}">
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>
                                {{ $order->user->name }} {{ $order->user->surname }}<br>
                                <span style="font-size:0.78rem;color:#aaa;">{{ $order->user->email }}</span>
                            </td>
                            <td>
                                <div class="order-items">
                                    @foreach($order->orderDetails as $detail)
                                        {{ $detail->quantity }}× {{ $detail->product->name }}<br>
                                    @endforeach
                                </div>
                            </td>
                            <td><strong>R{{ number_format($order->total, 2) }}</strong></td>
                            <td>
                                {{ \Carbon\Carbon::parse($order->collection_time)->format('H:i') }}<br>
                                <span style="font-size:0.78rem;color:#aaa;">
                                    {{ \Carbon\Carbon::parse($order->collection_time)->format('d M') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $order->status }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                @if($order->status === 'confirmed')
                                    <form method="POST"
                                          action="{{ route('pos.orders.collect', $order) }}"
                                          onsubmit="return confirm('Mark order #{{ $order->id }} as collected?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                style="background:var(--accent);color:var(--white);border:none;padding:0.4rem 0.85rem;font-size:0.8rem;font-weight:600;cursor:pointer;font-family:var(--font-body);border-radius:3px;">
                                            Mark collected
                                        </button>
                                    </form>
                                @elseif($order->status === 'pending')
                                    <span style="font-size:0.78rem;color:var(--slate);">Awaiting confirmation</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    function switchTab(name, btn) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + name).classList.add('active');
        btn.classList.add('active');
    }

    function lookupOrder() {
        const id = document.getElementById('orderIdInput').value.trim();
        const result = document.getElementById('orderResult');

        if (!id) {
            result.style.display = 'block';
            result.innerHTML = '<p style="color:var(--danger);font-size:0.875rem;">Please enter an order ID.</p>';
            return;
        }

        result.style.display = 'block';
        result.innerHTML = '<p style="color:var(--slate);font-size:0.875rem;">Looking up order #' + id + '...</p>';

        fetch('/pos/orders/' + id + '/lookup', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                result.innerHTML = '<p style="color:var(--danger);font-size:0.875rem;">' + data.error + '</p>';
                return;
            }

            let itemsHtml = data.items.map(i =>
                '<li>' + i.quantity + '× ' + i.product + '</li>'
            ).join('');

            result.innerHTML = `
                <div style="background:var(--base);padding:1rem;border-left:3px solid var(--accent);">
                    <p style="font-weight:600;margin-bottom:0.4rem;">Order #${data.id} — ${data.customer}</p>
                    <p style="font-size:0.82rem;color:var(--slate);margin-bottom:0.5rem;">
                        Status: <strong>${data.status}</strong> &nbsp;|&nbsp;
                        Total: <strong>R${data.total}</strong> &nbsp;|&nbsp;
                        Collect: <strong>${data.collection_time}</strong>
                    </p>
                    <ul style="font-size:0.82rem;color:var(--slate);padding-left:1.2rem;">${itemsHtml}</ul>
                </div>`;
        })
        .catch(() => {
            result.innerHTML = '<p style="color:var(--danger);font-size:0.875rem;">Could not look up that order. Check the ID and try again.</p>';
        });
    }
</script>
@endpush