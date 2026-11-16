<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request, ReportService $reports): View
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        // Last 7 days unless the admin picks a range
        $from = $request->date('from') ?? today()->subDays(6);
        $to = $request->date('to') ?? today();

        $daily = $reports->daily($from, $to);

        return view('admin.reports.index', [
            'from' => $from,
            'to' => $to,
            'daily' => $daily,
            'totals' => [
                'online' => $daily->sum('online'),
                'pos' => $daily->sum('pos'),
            ],
            'top' => $reports->topProducts($from, $to),
            'payments' => $reports->paymentSplit($from, $to),
        ]);
    }
}
