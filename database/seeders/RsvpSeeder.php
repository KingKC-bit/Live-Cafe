<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Database\Seeder;

class RsvpSeeder extends Seeder
{
    public function run(): void
    {
        $sarah = User::where('email', 'sarah@livecafe.test')->firstOrFail();
        $thando = User::where('email', 'thando@livecafe.test')->firstOrFail();
        $lwazi = User::where('email', 'lwazi@livecafe.test')->firstOrFail();
        $aphiwe = User::where('email', 'aphiwe@livecafe.test')->firstOrFail();
        $kamo = User::where('email', 'kamo@livecafe.test')->firstOrFail();
        $zanele = User::where('email', 'zanele@livecafe.test')->firstOrFail();

        $upcoming = Event::query()->upcoming()->chronological()->firstOrFail();
        $past = Event::query()->past()->orderByDesc('event_date')->firstOrFail();

        // The upcoming Saturday run.
        Rsvp::create(['user_id' => $sarah->id, 'event_id' => $upcoming->id, 'extras' => 1]);
        Rsvp::create(['user_id' => $thando->id, 'event_id' => $upcoming->id, 'extras' => 2]);
        Rsvp::create(['user_id' => $lwazi->id, 'event_id' => $upcoming->id, 'extras' => 0]);

        // Last week's run, including one cancelled RSVP that stays on record.
        Rsvp::create(['user_id' => $aphiwe->id, 'event_id' => $past->id, 'extras' => 1]);
        Rsvp::create(['user_id' => $kamo->id, 'event_id' => $past->id, 'extras' => 0]);
        Rsvp::create(['user_id' => $zanele->id, 'event_id' => $past->id, 'extras' => 2]);

        $cancelled = Rsvp::create(['user_id' => $sarah->id, 'event_id' => $past->id, 'extras' => 0]);
        $cancelled->forceFill([
            'status' => Rsvp::STATUS_CANCELLED,
            'cancelled_at' => now()->subDays(8),
        ])->save();

        $upcoming->refreshAttendance();
        $past->refreshAttendance();
    }
}
