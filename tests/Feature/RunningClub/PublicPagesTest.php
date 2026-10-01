<?php

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use Carbon\CarbonImmutable;

beforeEach(function () {
    // Thursday lunchtime, two days before a Saturday 07:00 run.
    $this->travelTo(CarbonImmutable::parse('2026-10-01 12:00'));
});

test('anyone can see upcoming runs but not past ones', function () {
    Event::factory()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create(['title' => 'Saturday Run']);
    Event::factory()->startingAt(CarbonImmutable::parse('2026-09-26 07:00'))->create(['title' => 'Last Week Run']);

    $this->get(route('running.index'))
        ->assertOk()
        ->assertSee('Saturday Run')
        ->assertSee('Closes 23:00 Fri')
        ->assertDontSee('Last Week Run');
});

test('a run drops off the list once it starts', function () {
    $this->travelTo(CarbonImmutable::parse('2026-10-03 08:00'));

    Event::factory()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create(['title' => 'Morning Run']);
    Event::factory()->startingAt(CarbonImmutable::parse('2026-10-03 17:30'))->create(['title' => 'Evening Run']);

    $this->get(route('running.index'))
        ->assertSee('Evening Run')
        ->assertDontSee('Morning Run');
});

test('cancelled runs stay on the list, marked as cancelled', function () {
    Event::factory()->cancelled()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create(['title' => 'Rained Out Run']);

    $this->get(route('running.index'))
        ->assertSee('Rained Out Run')
        ->assertSee('Cancelled');
});

test('only admins can see who is going or how many', function () {
    $event = Event::factory()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create();
    $runner = User::factory()->create(['name' => 'Zinhle', 'surname' => 'Unlikelysurname']);
    Rsvp::factory()->for($event)->for($runner)->create(['extras' => 2]);
    $event->forceFill(['total_attendees' => 4321])->save();

    $pages = [route('running.index'), route('running.events.show', $event)];

    foreach ($pages as $page) {
        $this->get($page)->assertOk()->assertDontSee('Unlikelysurname')->assertDontSee('4321');
    }

    $this->actingAs(User::factory()->create());

    foreach ($pages as $page) {
        $this->get($page)->assertOk()->assertDontSee('Unlikelysurname')->assertDontSee('4321');
    }
});

test('a member sees their own RSVP on the list', function () {
    $event = Event::factory()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create();
    $member = User::factory()->create();
    Rsvp::factory()->for($event)->for($member)->create(['extras' => 1]);

    $this->actingAs($member)
        ->get(route('running.index'))
        ->assertSee('Going +1')
        ->assertSee('My RSVPs');
});

test('guests are invited to sign in and come back to the run', function () {
    $event = Event::factory()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create();

    $this->get(route('running.events.show', $event))
        ->assertSee('RSVPs close Fri 2 Oct, 23:00.')
        ->assertSee(route('running.sign-in', ['event' => $event->id]), false)
        ->assertSee(route('running.join', ['event' => $event->id]), false);
});

test('a past run says when it took place', function () {
    $event = Event::factory()->startingAt(CarbonImmutable::parse('2026-09-26 07:00'))->create();

    $this->get(route('running.events.show', $event))
        ->assertOk()
        ->assertSee('This run took place on Saturday 26 September.');
});

test('draft announcements stay private', function () {
    $published = Announcement::factory()->create(['title' => 'Route change']);
    $draft = Announcement::factory()->draft()->create(['title' => 'Secret merch drop']);

    $this->get(route('running.index'))->assertSee('Route change')->assertDontSee('Secret merch drop');
    $this->get(route('running.announcements.index'))->assertSee('Route change')->assertDontSee('Secret merch drop');

    $this->get(route('running.announcements.show', $published))->assertOk();
    $this->get(route('running.announcements.show', $draft))->assertNotFound();
});

test('a pinned announcement stays on top of newer ones', function () {
    Announcement::factory()->pinned()->create(['title' => 'Pinned notice', 'published_at' => now()->subDays(5)]);
    Announcement::factory()->create(['title' => 'Newer notice', 'published_at' => now()->subHour()]);

    $this->get(route('running.index'))
        ->assertSee('Pinned notice')
        ->assertDontSee('Newer notice')
        ->assertSee('All announcements (2)');
});

test('the home page lists upcoming runs by title and leaves out cancelled ones', function () {
    Event::factory()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create(['title' => 'Saturday Run']);
    Event::factory()->cancelled()->startingAt(CarbonImmutable::parse('2026-10-04 07:00'))->create(['title' => 'Called Off Run']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Saturday Run')
        ->assertDontSee('Called Off Run')
        ->assertDontSee('see who else is joining');
});
