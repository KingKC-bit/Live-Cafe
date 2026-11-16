<?php

use App\Models\Sale;
use App\Models\User;
use App\Services\Admin\ReportService;

it('builds one row per day, including days with no sales', function () {
    $rows = app(ReportService::class)->daily(today()->subDays(6), today());

    expect($rows)->toHaveCount(7);
});

it('leaves voided sales out of the daily totals', function () {
    Sale::factory()->create(['status' => 'confirmed', 'total' => 50, 'occurred_at' => today()]);
    Sale::factory()->create(['status' => 'cancelled', 'total' => 500, 'occurred_at' => today()]);

    $today = app(ReportService::class)->daily(today(), today())->first();

    expect($today['pos'])->toEqual(50.0);
});

it('rejects a range that ends before it starts', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.reports.index', ['from' => '2026-10-10', 'to' => '2026-10-01']))
        ->assertSessionHasErrors('to');
});
