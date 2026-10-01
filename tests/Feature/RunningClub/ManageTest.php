<?php

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use App\Notifications\RunningClub\RunCancelled;
use App\Notifications\RunningClub\RunChanged;
use App\Services\RunningClub\RunningClubService;
use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Notification::fake();

    $this->travelTo(CarbonImmutable::parse('2026-10-01 12:00'));

    $this->admin = User::factory()->admin()->create();
    $this->event = Event::factory()->startingAt(CarbonImmutable::parse('2026-10-03 07:00'))->create([
        'title' => 'Saturday Run',
        'address' => '102 Rivonia Road, Sandton',
        'distance_km' => 5,
    ]);
});

/**
 * What the add/edit form posts for a run.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function runClubFormData(array $overrides = []): array
{
    return array_merge([
        'type' => 'run',
        'title' => 'Sunrise 10K',
        'event_date' => '2026-10-10',
        'event_time' => '06:30',
        'address' => '102 Rivonia Road, Sandton',
        'distance_km' => '10',
        'pace' => 'Easy',
        'dress_code' => '',
        'sponsor' => '',
        'description' => '',
    ], $overrides);
}

/**
 * The form data for the Saturday Run in beforeEach, with some fields changed.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function saturdayRunFormData(array $overrides = []): array
{
    return runClubFormData(array_merge([
        'title' => 'Saturday Run',
        'event_date' => '2026-10-03',
        'event_time' => '07:00',
        'distance_km' => '5',
    ], $overrides));
}

test('only admins can open the management pages', function () {
    $this->get(route('running.manage.index'))->assertRedirect(route('login'));

    $this->actingAs(User::factory()->create());

    $this->get(route('running.manage.index'))->assertForbidden();
    $this->get(route('running.manage.events.create'))->assertForbidden();
    $this->post(route('running.manage.events.store'), runClubFormData())->assertForbidden();
    $this->get(route('running.manage.events.rsvps', $this->event))->assertForbidden();
    $this->get(route('running.manage.events.rsvps.export', $this->event))->assertForbidden();
    $this->patch(route('running.manage.events.cancel', $this->event))->assertForbidden();
    $this->get(route('running.manage.announcements.index'))->assertForbidden();

    $this->actingAs(User::factory()->staff()->create())
        ->get(route('running.manage.index'))
        ->assertForbidden();

    expect(Event::query()->count())->toBe(1)
        ->and($this->event->fresh()->isCancelled())->toBeFalse();
});

test('every management page opens for an admin', function () {
    $announcement = Announcement::factory()->create();

    $this->actingAs($this->admin);

    $pages = [
        route('running.manage.index'),
        route('running.manage.events.create'),
        route('running.manage.events.edit', $this->event),
        route('running.manage.events.rsvps', $this->event),
        route('running.manage.announcements.index'),
        route('running.manage.announcements.create'),
        route('running.manage.announcements.edit', $announcement),
    ];

    foreach ($pages as $page) {
        $this->get($page)->assertOk();
    }
});

test('admins see the expected headcount for each run', function () {
    $service = app(RunningClubService::class);
    $service->saveRsvp(User::factory()->create(), $this->event, 1);
    $service->saveRsvp(User::factory()->create(), $this->event, 2);

    $this->actingAs($this->admin)
        ->get(route('running.manage.index'))
        ->assertSee('Saturday Run')
        ->assertSee('5 people')
        ->assertSee('2 members + 3 extra');
});

test('admins can add a run', function () {
    $this->actingAs($this->admin)
        ->post(route('running.manage.events.store'), runClubFormData())
        ->assertRedirect(route('running.manage.index'))
        ->assertSessionHas('success', 'Added Sunrise 10K on Sat 10 Oct.');

    $run = Event::query()->where('title', 'Sunrise 10K')->sole();

    expect($run->isRun())->toBeTrue()
        ->and($run->event_date->format('Y-m-d'))->toBe('2026-10-10')
        ->and($run->event_time)->toBe('06:30:00')
        ->and($run->distance_km)->toBe(10.0)
        ->and($run->pace)->toBe('Easy')
        ->and($run->dress_code)->toBeNull()
        ->and($run->description)->toBe('')
        ->and($run->isCancelled())->toBeFalse()
        ->and($run->rsvpClosesAt()->format('Y-m-d H:i'))->toBe('2026-10-09 22:30');

    $this->get(route('running.index'))->assertSee('Sunrise 10K');
});

test('a run needs a distance but an event does not', function () {
    $this->actingAs($this->admin);

    $this->post(route('running.manage.events.store'), runClubFormData(['distance_km' => '']))
        ->assertSessionHasErrors(['distance_km' => 'Add the distance for a run.']);

    $this->post(route('running.manage.events.store'), runClubFormData([
        'type' => 'event',
        'title' => 'Brand Shakeout',
        'distance_km' => '',
        'sponsor' => 'Nike',
    ]))->assertSessionHasNoErrors();

    $event = Event::query()->where('title', 'Brand Shakeout')->sole();

    expect($event->isRun())->toBeFalse()
        ->and($event->distance_km)->toBeNull()
        ->and($event->sponsor)->toBe('Nike');

    $this->get(route('running.index'))->assertSee('Presented by Nike');
});

test('a new run cannot be dated in the past', function () {
    $this->actingAs($this->admin)
        ->post(route('running.manage.events.store'), runClubFormData(['event_date' => '2026-09-30']))
        ->assertSessionHasErrors(['event_date' => 'The date must be today or later.']);

    expect(Event::query()->count())->toBe(1);
});

test('admins can add and remove a photo', function () {
    Storage::fake('public');

    $this->actingAs($this->admin)
        ->post(route('running.manage.events.store'), runClubFormData([
            'photo' => UploadedFile::fake()->image('run.jpg', 1200, 800),
        ]))
        ->assertSessionHasNoErrors();

    $run = Event::query()->where('title', 'Sunrise 10K')->sole();
    $path = $run->photo?->path;

    expect($path)->not->toBeNull()
        ->and($run->photoUrl())->toEndWith('storage/'.$path);
    Storage::disk('public')->assertExists((string) $path);

    $this->put(route('running.manage.events.update', $run), runClubFormData(['remove_photo' => '1']))
        ->assertSessionHasNoErrors();

    expect($run->fresh()?->photo)->toBeNull();
    Storage::disk('public')->assertMissing((string) $path);
});

test('changing the start time emails the members who are going', function () {
    $going = User::factory()->create();
    $changedMind = User::factory()->create();
    Rsvp::factory()->for($this->event)->for($going)->create();
    Rsvp::factory()->cancelled()->for($this->event)->for($changedMind)->create();

    $this->actingAs($this->admin)
        ->put(route('running.manage.events.update', $this->event), saturdayRunFormData(['event_time' => '07:30']))
        ->assertRedirect(route('running.manage.index'));

    expect($this->event->fresh()->event_time)->toBe('07:30:00');

    Notification::assertSentTo($going, RunChanged::class, fn (RunChanged $notification) => $notification->changes === ['Start time' => '07:30']);
    Notification::assertNotSentTo($changedMind, RunChanged::class);
});

test('changing the date and place lists both in the email', function () {
    $going = User::factory()->create();
    Rsvp::factory()->for($this->event)->for($going)->create();

    $this->actingAs($this->admin)->put(route('running.manage.events.update', $this->event), saturdayRunFormData([
        'event_date' => '2026-10-04',
        'address' => 'Sandton Central',
    ]));

    Notification::assertSentTo($going, RunChanged::class, fn (RunChanged $notification) => $notification->changes === [
        'Date' => 'Sunday 4 October 2026',
        'Location' => 'Sandton Central',
    ]);
});

test('other edits do not email anyone', function () {
    Rsvp::factory()->for($this->event)->create();

    $this->actingAs($this->admin)
        ->put(route('running.manage.events.update', $this->event), saturdayRunFormData(['description' => 'Bring water.']))
        ->assertSessionHasNoErrors();

    expect($this->event->fresh()->description)->toBe('Bring water.');
    Notification::assertNothingSent();
});

test('cancelling a run keeps its RSVPs and emails the members who are going', function () {
    $going = User::factory()->create();
    Rsvp::factory()->for($this->event)->for($going)->create();

    $this->actingAs($this->admin)
        ->patch(route('running.manage.events.cancel', $this->event))
        ->assertRedirect(route('running.manage.index'));

    expect($this->event->fresh()->isCancelled())->toBeTrue()
        ->and(Rsvp::query()->count())->toBe(1);

    Notification::assertSentTo($going, RunCancelled::class);
});

test('a run with RSVPs can be cancelled but not deleted', function () {
    Rsvp::factory()->cancelled()->for($this->event)->create();

    $this->actingAs($this->admin)
        ->delete(route('running.manage.events.destroy', $this->event))
        ->assertSessionHas('error');

    expect(Event::query()->find($this->event->id))->not->toBeNull();
});

test('a run nobody has RSVPd to can be deleted', function () {
    $this->actingAs($this->admin)
        ->delete(route('running.manage.events.destroy', $this->event))
        ->assertSessionHas('success', 'Deleted Saturday Run.');

    expect(Event::query()->find($this->event->id))->toBeNull();
});

test('duplicating a run fills the form with its details a week later', function () {
    $this->actingAs($this->admin)
        ->get(route('running.manage.events.create', ['from' => $this->event->id]))
        ->assertOk()
        ->assertSee('value="Saturday Run"', false)
        ->assertSee('value="2026-10-10"', false)
        ->assertSee('value="07:00"', false)
        ->assertSee('RSVPs close Fri 9 Oct at 23:00');

    expect(Event::query()->count())->toBe(1);
});

test('admins can see who is going', function () {
    $runner = User::factory()->create(['name' => 'Thando', 'surname' => 'Maseko']);
    Rsvp::factory()->for($this->event)->for($runner)->create(['extras' => 2]);

    $this->actingAs($this->admin)
        ->get(route('running.manage.events.rsvps', $this->event))
        ->assertOk()
        ->assertSee('Thando Maseko')
        ->assertSee($runner->email);
});

test('the RSVP export is a CSV that spreadsheets cannot run as a formula', function () {
    $runner = User::factory()->create(['name' => '=HYPERLINK("http://example.com")', 'surname' => 'Smith']);
    Rsvp::factory()->for($this->event)->for($runner)->create(['extras' => 1]);

    $response = $this->actingAs($this->admin)
        ->get(route('running.manage.events.rsvps.export', $this->event))
        ->assertOk()
        ->assertDownload('saturday-run-2026-10-03-rsvps.csv');

    $csv = $response->streamedContent();

    expect($csv)->toContain('Name,Surname,Email,Phone,"Extra runners",Headcount,Status,"RSVP date"')
        ->and($csv)->toContain('"\'=HYPERLINK(""http://example.com"")",Smith,')
        ->and($csv)->toContain(',1,2,Going,');
});

test('admins can publish an announcement or save it as a draft', function () {
    $this->actingAs($this->admin);

    $this->post(route('running.manage.announcements.store'), [
        'title' => 'New route',
        'description' => 'We run the river loop this week.',
        'action' => 'publish',
    ])->assertRedirect(route('running.manage.announcements.index'));

    $this->post(route('running.manage.announcements.store'), [
        'title' => 'Merch soon',
        'description' => 'Club shirts are on the way.',
        'action' => 'draft',
    ]);

    $published = Announcement::query()->where('title', 'New route')->sole();
    $draft = Announcement::query()->where('title', 'Merch soon')->sole();

    expect($published->isPublished())->toBeTrue()
        ->and($published->user_id)->toBe($this->admin->id)
        ->and($draft->isPublished())->toBeFalse();

    $this->get(route('running.announcements.index'))
        ->assertSee('New route')
        ->assertDontSee('Merch soon');
});

test('editing a published announcement keeps its original date', function () {
    $announcement = Announcement::factory()->create(['published_at' => CarbonImmutable::parse('2026-09-20 09:00')]);

    $this->actingAs($this->admin)->put(route('running.manage.announcements.update', $announcement), [
        'title' => 'Fixed a typo',
        'description' => 'Body',
        'action' => 'publish',
    ]);

    expect($announcement->fresh()?->title)->toBe('Fixed a typo')
        ->and($announcement->fresh()?->published_at?->format('Y-m-d H:i'))->toBe('2026-09-20 09:00');
});

test('saving a published announcement as a draft hides it again', function () {
    $announcement = Announcement::factory()->create(['title' => 'Old news']);

    $this->actingAs($this->admin)->put(route('running.manage.announcements.update', $announcement), [
        'title' => 'Old news',
        'description' => 'Body',
        'action' => 'draft',
    ]);

    expect($announcement->fresh()?->isPublished())->toBeFalse();
    $this->get(route('running.announcements.show', $announcement))->assertNotFound();
});

test('admins can delete an announcement', function () {
    $announcement = Announcement::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('running.manage.announcements.destroy', $announcement))
        ->assertRedirect(route('running.manage.announcements.index'));

    expect(Announcement::query()->count())->toBe(0);
});
