<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\URL;

test('signing in from a run brings the member back to that run', function () {
    $event = Event::factory()->create();
    $member = User::factory()->create();

    $this->get(route('running.sign-in', ['event' => $event->id]))
        ->assertRedirect(route('login'));

    $this->post(route('login.store'), ['email' => $member->email, 'password' => 'password'])
        ->assertRedirect(route('running.events.show', $event).'#rsvp');
});

test('signing in from the run club page brings the member back to it', function () {
    $member = User::factory()->create();

    $this->get(route('running.sign-in'))->assertRedirect(route('login'));

    $this->post(route('login.store'), ['email' => $member->email, 'password' => 'password'])
        ->assertRedirect(route('running.index'));
});

test('only a run id is accepted, so the link cannot send people to another site', function () {
    $member = User::factory()->create();

    $this->get(route('running.sign-in', ['event' => 'https://example.com']))->assertRedirect(route('login'));

    $this->post(route('login.store'), ['email' => $member->email, 'password' => 'password'])
        ->assertRedirect(route('running.index'));
});

test('members who are already signed in go straight back to the run', function () {
    $event = Event::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('running.sign-in', ['event' => $event->id]))
        ->assertRedirect(route('running.events.show', $event).'#rsvp');
});

test('a new member who signs up from a run comes back to it after verifying their email', function () {
    $event = Event::factory()->create();

    $this->get(route('running.join', ['event' => $event->id]))
        ->assertRedirect(route('register'));

    $this->post(route('register.store'), [
        'name' => 'Naledi',
        'surname' => 'Khumalo',
        'email' => 'naledi@example.com',
        'phone_number' => '0712345678',
        'password' => 'Correct-Horse-Battery-9',
        'password_confirmation' => 'Correct-Horse-Battery-9',
    ])->assertRedirect(route('verification.notice'));

    $member = User::query()->where('email', 'naledi@example.com')->sole();

    $verificationUrl = URL::temporarySignedRoute('verification.verify', now()->addHour(), [
        'id' => $member->id,
        'hash' => sha1($member->email),
    ]);

    $this->get($verificationUrl)
        ->assertRedirect(route('running.events.show', $event).'#rsvp');

    expect($member->fresh()?->hasVerifiedEmail())->toBeTrue();
});

test('without a run to return to, a verified member still lands on the shop', function () {
    $member = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute('verification.verify', now()->addHour(), [
        'id' => $member->id,
        'hash' => sha1($member->email),
    ]);

    $this->actingAs($member)
        ->get($verificationUrl)
        ->assertRedirect(route('shop.index'));
});
