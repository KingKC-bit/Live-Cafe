{{-- resources/views/admin/reports/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Reports — Admin')

@section('admin-content')
<h1>Reports</h1>
<p class="admin-sub">Revenue from the shop and the till, best sellers, and cash vs card. Voided sales and cancelled orders are left out.</p>

@if ($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<form method="GET" action="{{ route('admin.reports.index') }}" class="filter-bar">
    <label>From
        <input type="date" name="from" value="{{ $from->toDateString() }}">
    </label>
    <label>To
        <input type="date" name="to" value="{{ $to->toDateString() }}">
    </label>
    <button type="submit">Show</button>
</form>

<div class="stat-grid">
    <div class="stat-card">
        <p class="stat-card-label">Total revenue</p>
        <p class="stat-card-value">R {{ number_format($totals['online'] + $totals['pos'], 2) }}</p>
        <p class="stat-card-note">{{ $from->format('j M') }} to {{ $to->format('j M Y') }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-card-label">Online shop</p>
        <p class="stat-card-value">R {{ number_format($totals['online'], 2) }}</p>
    </div>
    <div class="stat-card">
        <p class="stat-card-label">Till</p>
        <p class="stat-card-value">R {{ number_format($totals['pos'], 2) }}</p>
    </div>
</div>

<div class="admin-section">
    <div class="admin-section-header">
        <h2>Revenue per day</h2>
    </div>
    <div style="position: relative; height: 260px;">
        <canvas id="dailyChart" aria-label="Revenue per day, online and till" role="img"></canvas>
    </div>
</div>

<div class="admin-section">
    <div class="admin-section-header">
        <h2>Top sellers</h2>
    </div>
    <table class="data-table">
        <tr><th>Product</th><th>Sold</th><th>Revenue</th></tr>
        @forelse ($top as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['qty'] }}</td>
                <td>R {{ number_format($row['revenue'], 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="empty">No sales in this range.</td></tr>
        @endforelse
    </table>
</div>

<div class="admin-section">
    <div class="admin-section-header">
        <h2>Cash vs card (till)</h2>
    </div>
    <table class="data-table">
        <tr><th>Method</th><th>Sales</th><th>Revenue</th></tr>
        @forelse ($payments as $payment)
            <tr>
                <td>{{ ucfirst($payment->payment_method) }}</td>
                <td>{{ $payment->getAttribute('count') }}</td>
                <td>R {{ number_format($payment->getAttribute('revenue'), 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="empty">No till sales in this range.</td></tr>
        @endforelse
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const daily = @json($daily);
    const css = getComputedStyle(document.documentElement);

    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels: daily.map(d => d.day),
            datasets: [
                { label: 'Online (R)', data: daily.map(d => d.online), borderColor: css.getPropertyValue('--accent'), tension: 0.25 },
                { label: 'Till (R)',   data: daily.map(d => d.pos),    borderColor: css.getPropertyValue('--green'),  tension: 0.25 },
            ],
        },
        options: {
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } },
        },
    });
</script>
@endpush