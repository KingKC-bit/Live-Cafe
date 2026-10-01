<?php

use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use App\Notifications\RunningClub\RsvpConfirmed;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();

    // Thursday lunchtime. The run starts Saturday 07:00, so RSVPs close
    // Friday 23:00 (the app runs on South African time).
    $this->travelTo(CarbonImmutable::parse('2026-10-01 12:00'));

    $this->event = Event::factory()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create();
    $this->member = User::factory()->create();
});

test('a member can RSVP and bring extra runners', function () {
    $this->actingAs($this->member)
        ->post(route('running.rsvp.store', $this->event), ['extras' => 2])
        ->assertRedirect(route('running.events.show', $this->event).'#rsvp')
        ->assertSessionHas('success', "You're going! We've emailed you the details.");

    $rsvp = Rsvp::query()->sole();

    expect($rsvp->user_id)->toBe($this->member->id)
        ->and($rsvp->extras)->toBe(2)
        ->and($rsvp->isGoing())->toBeTrue()
        ->and($this->event->fresh()->total_attendees)->toBe(3);

    Notification::assertSentTo($this->member, RsvpConfirmed::class, fn (RsvpConfirmed $notification) => $notification->extras === 2);
});

test('RSVPing again changes the extra runners instead of adding a second RSVP', function () {
    $this->actingAs($this->member);

    $this->post(route('running.rsvp.store', $this->event), ['extras' => 0]);
    $this->post(route('running.rsvp.store', $this->event), ['extras' => 3])
        ->assertSessionHas('success', 'Your RSVP is updated.');

    expect(Rsvp::query()->sole()->extras)->toBe(3)
        ->and($this->event->fresh()->total_attendees)->toBe(4);

    Notification::assertSentToTimes($this->member, RsvpConfirmed::class, 1);
});

test('the headcount counts each member going plus their extra runners', function () {
    foreach ([0, 2, 1] as $extras) {
        $this->actingAs(User::factory()->create())
            ->post(route('running.rsvp.store', $this->event), ['extras' => $extras]);
    }

    expect($this->event->fresh()->total_attendees)->toBe(6);

    $this->delete(route('running.rsvp.destroy', $this->event));

    expect($this->event->fresh()->total_attendees)->toBe(4);
});

test('extra runners must be a whole number from 0 to 99', function (mixed $extras) {
    $this->actingAs($this->member)
        ->post(route('running.rsvp.store', $this->event), ['extras' => $extras])
        ->assertSessionHasErrors('extras');

    expect(Rsvp::query()->count())->toBe(0);
})->with([-1, 100, 1.5, 'two', null]);

test('guests have to sign in to RSVP', function () {
    $this->post(route('running.rsvp.store', $this->event), ['extras' => 0])
        ->assertRedirect(route('login'));

    expect(Rsvp::query()->count())->toBe(0);
});

test('members have to verify their email to RSVP', function () {
    $this->actingAs(User::factory()->unverified()->create())
        ->post(route('running.rsvp.store', $this->event), ['extras' => 0])
        ->assertRedirect(route('verification.notice'));

    expect(Rsvp::query()->count())->toBe(0);
});

test('RSVPs close eight hours before the start', function () {
    $this->travelTo(CarbonImmutable::parse('2026-10-02 22:59'));
    expect($this->event->rsvpIsOpen())->toBeTrue();

    $this->travelTo(CarbonImmutable::parse('2026-10-02 23:00'));
    expect($this->event->rsvpIsOpen())->toBeFalse();

    $this->actingAs($this->member)
        ->post(route('running.rsvp.store', $this->event), ['extras' => 0])
        ->assertSessionHas('error', 'RSVPs for this run closed at 23:00 on Fri 2 Oct.');

    expect(Rsvp::query()->count())->toBe(0);
});

test('a member can still join a minute before RSVPs close', function () {
    $this->travelTo(CarbonImmutable::parse('2026-10-02 22:59'));

    $this->actingAs($this->member)
        ->post(route('running.rsvp.store', $this->event), ['extras' => 0])
        ->assertSessionHas('success');

    expect(Rsvp::query()->sole()->isGoing())->toBeTrue();
});

test('after RSVPs close, a member who is going can bring fewer people or cancel', function () {
    $this->actingAs($this->member)->post(route('running.rsvp.store', $this->event), ['extras' => 3]);

    $this->travelTo(CarbonImmutable::parse('2026-10-03 01:00'));

    $this->get(route('running.events.show', $this->event))
        ->assertSee('RSVPs have closed, but you can still bring fewer people.')
        ->assertSee('max="3"', false);

    $this->post(route('running.rsvp.store', $this->event), ['extras' => 1])
        ->assertSessionHas('success', 'Your RSVP is updated.');

    expect($this->event->fresh()->total_attendees)->toBe(2);

    $this->delete(route('running.rsvp.destroy', $this->event))
        ->assertSessionHas('success', 'Your RSVP is cancelled.');

    expect($this->event->fresh()->total_attendees)->toBe(0)
        ->and(Rsvp::query()->sole()->status)->toBe(Rsvp::STATUS_CANCELLED);
});

test('after RSVPs close, extra runners cannot be added', function () {
    $this->actingAs($this->member)->post(route('running.rsvp.store', $this->event), ['extras' => 1]);

    $this->travelTo(CarbonImmutable::parse('2026-10-03 01:00'));

    $this->post(route('running.rsvp.store', $this->event), ['extras' => 2])
        ->assertSessionHas('error', 'RSVPs closed at 23:00 on Fri 2 Oct. You can still lower your extra runners or cancel your RSVP.');

    expect(Rsvp::query()->sole()->extras)->toBe(1);
});

test('after RSVPs close, a cancelled RSVP cannot be restarted', function () {
    $this->actingAs($this->member);
    $this->post(route('running.rsvp.store', $this->event), ['extras' => 0]);
    $this->delete(route('running.rsvp.destroy', $this->event));

    $this->travelTo(CarbonImmutable::parse('2026-10-03 01:00'));

    $this->get(route('running.events.show', $this->event))
        ->assertSee('RSVPs for this run have closed.');

    $this->post(route('running.rsvp.store', $this->event), ['extras' => 0])
        ->assertSessionHas('error', 'RSVPs for this run closed at 23:00 on Fri 2 Oct.');

    expect(Rsvp::query()->sole()->isGoing())->toBeFalse();
});

test('before RSVPs close, a member can cancel and join again', function () {
    $this->actingAs($this->member);

    $this->post(route('running.rsvp.store', $this->event), ['extras' => 1]);
    $this->delete(route('running.rsvp.destroy', $this->event));
    $this->post(route('running.rsvp.store', $this->event), ['extras' => 0])
        ->assertSessionHas('success', "You're going! We've emailed you the details.");

    $rsvp = Rsvp::query()->sole();

    expect($rsvp->isGoing())->toBeTrue()
        ->and($rsvp->extras)->toBe(0)
        ->and($rsvp->cancelled_at)->toBeNull();

    Notification::assertSentToTimes($this->member, RsvpConfirmed::class, 2);
});

test('nobody can RSVP to a cancelled run', function () {
    $event = Event::factory()->cancelled()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create();

    $this->actingAs($this->member)
        ->post(route('running.rsvp.store', $event), ['extras' => 0])
        ->assertSessionHas('error', 'This run has been cancelled, so RSVPs are closed.');

    expect(Rsvp::query()->count())->toBe(0);
});

test('RSVPs cannot change once the run has started', function () {
    $this->actingAs($this->member)->post(route('running.rsvp.store', $this->event), ['extras' => 2]);

    $this->travelTo(CarbonImmutable::parse('2026-10-03 07:00'));

    $this->post(route('running.rsvp.store', $this->event), ['extras' => 0])
        ->assertSessionHas('error', 'This run has already started, so RSVPs can no longer be changed.');
    $this->delete(route('running.rsvp.destroy', $this->event))
        ->assertSessionHas('error', 'This run has already started, so RSVPs can no longer be changed.');

    expect(Rsvp::query()->sole()->extras)->toBe(2)
        ->and(Rsvp::query()->sole()->isGoing())->toBeTrue();
});

test('the messages call an event an event', function () {
    $event = Event::factory()->event()->cancelled()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create();

    $this->actingAs($this->member)
        ->post(route('running.rsvp.store', $event), ['extras' => 0])
        ->assertSessionHas('error', 'This event has been cancelled, so RSVPs are closed.');
});

test('cancelling without an RSVP gives a friendly message', function () {
    $this->actingAs($this->member)
        ->delete(route('running.rsvp.destroy', $this->event))
        ->assertSessionHas('error', "You don't have an RSVP for this run.");
});

test('My RSVPs lists the upcoming runs a member is going to', function () {
    $this->event->update(['title' => 'Going Run']);
    Rsvp::factory()->for($this->event)->for($this->member)->create();

    $changedMind = Event::factory()->startingAt(CarbonImmutable::parse('2026-10-10 07:00'))->create(['title' => 'Changed Mind Run']);
    Rsvp::factory()->cancelled()->for($changedMind)->for($this->member)->create();

    $past = Event::factory()->startingAt(CarbonImmutable::parse('2026-09-26 07:00'))->create(['title' => 'Last Week Run']);
    Rsvp::factory()->for($past)->for($this->member)->create();

    $someoneElses = Event::factory()->startingAt(CarbonImmutable::parse('2026-10-17 07:00'))->create(['title' => 'Someone Elses Run']);
    Rsvp::factory()->for($someoneElses)->create();

    $this->actingAs($this->member)
        ->get(route('running.rsvp.index'))
        ->assertOk()
        ->assertSee('Going Run')
        ->assertDontSee('Changed Mind Run')
        ->assertDontSee('Last Week Run')
        ->assertDontSee('Someone Elses Run');
});

test('the confirmation email has the run details', function () {
    $this->event->update(['title' => 'Saturday Run', 'address' => '102 Rivonia Road, Sandton', 'dress_code' => 'Black and pink']);

    $mail = (new RsvpConfirmed($this->event, 2))->toMail($this->member);

    expect($mail->subject)->toBe("You're in: Saturday Run, Sat 3 Oct")
        ->and($mail->introLines)->toContain('**When:** Saturday 3 October 2026 at 07:00')
        ->and($mail->introLines)->toContain('**Where:** 102 Rivonia Road, Sandton')
        ->and($mail->introLines)->toContain('**Dress code:** Black and pink')
        ->and($mail->introLines)->toContain("You said you're bringing 2 extra runners.")
        ->and($mail->actionUrl)->toBe(route('running.events.show', $this->event));
});
