<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Sale;
use App\Models\SalesDetail;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class ReportService
{
    /** Revenue per day, split by channel. */
    public function daily(CarbonInterface $from, CarbonInterface $to): Collection
    {
        $online = Order::whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->whereIn('status', ['confirmed', 'collected'])
            ->selectRaw('DATE(created_at) as day, SUM(total) as revenue')
            ->groupBy('day')->pluck('revenue', 'day');

        $pos = Sale::whereBetween('occurred_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->where('status', 'confirmed')
            ->selectRaw('DATE(occurred_at) as day, SUM(total) as revenue')
            ->groupBy('day')->pluck('revenue', 'day');

        // one row per calendar day, even days with no sales (so the chart has no gaps)
        return collect($from->copy()->toPeriod($to))->map(function ($date) use ($online, $pos) {
            $d = $date->toDateString();

            return [
                'day' => $d,
                'online' => (float) ($online[$d] ?? 0),
                'pos' => (float) ($pos[$d] ?? 0),
            ];
        });
    }

    /** Best sellers across both channels. */
    public function topProducts(CarbonInterface $from, CarbonInterface $to, int $limit = 10): Collection
    {
        $range = [$from->copy()->startOfDay(), $to->copy()->endOfDay()];

        $online = OrderDetail::with('product')
            ->whereHas('order', fn ($q) => $q->whereIn('status', ['confirmed', 'collected'])->whereBetween('created_at', $range))
            ->get();

        $pos = SalesDetail::with('product')
            ->whereHas('sale', fn ($q) => $q->where('status', 'confirmed')->whereBetween('occurred_at', $range))
            ->get();

        return $online->concat($pos)
            ->groupBy('product_id')
            ->map(fn ($lines) => [
                'name' => $lines->first()->product->getAttribute('name'),
                'qty' => $lines->sum('quantity'),
                'revenue' => $lines->sum(fn ($l) => $l->quantity * $l->unit_price),
            ])
            ->sortByDesc('qty')
            ->take($limit)
            ->values();
    }

    /** Cash vs card at the till. */
    public function paymentSplit(CarbonInterface $from, CarbonInterface $to): Collection
    {
        return Sale::whereBetween('occurred_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->where('status', 'confirmed')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('payment_method')
            ->get();
    }
}
