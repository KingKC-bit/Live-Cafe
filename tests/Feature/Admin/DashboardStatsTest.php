<?php

use App\Models\Event;
use App\Models\Rsvp;
use App\Models\Sale;
use App\Models\User;

beforeEach(fn () => $this->actingAs(User::factory()->admin()->create()));

it('returns live stats as JSON', function () {
    $this->getJson(route('admin.stats'))
        ->assertOk()
        ->assertJsonStructure(['online_revenue', 'pos_revenue', 'pending_orders', 'low_stock', 'open_conflicts', 'next_event', 'updated_at']);
});

it('leaves voided sales out of revenue', function () {
    Sale::factory()->create(['status' => 'confirmed', 'total' => 100, 'occurred_at' => now()]);
    Sale::factory()->create(['status' => 'cancelled', 'total' => 999, 'occurred_at' => now()]);

    expect($this->getJson(route('admin.stats'))->json('pos_revenue'))->toEqual(100);
});

it('leaves cancelled RSVPs out of the next run count', function () {
    $run = Event::factory()->create(['event_date' => today()->addDay()->toDateString()]);
    Rsvp::factory()->create(['event_id' => $run->id, 'extras' => 1]);
    Rsvp::factory()->cancelled()->create(['event_id' => $run->id, 'extras' => 2]);

    expect($this->getJson(route('admin.stats'))->json('next_event.expected'))->toBe(2);   // 1 person + 1 extra
});

it('does not give customers the stats', function () {
    $this->actingAs(User::factory()->customer()->create())
        ->getJson(route('admin.stats'))
        ->assertForbidden();
});
