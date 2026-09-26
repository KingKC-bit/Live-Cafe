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

        $events = Event::orderBy('event_date')
            ->orderBy('event_time')
            ->get();

        $eventOne = $events->get(0);
        $eventTwo = $events->get(1);
        $eventThree = $events->get(2);

        Rsvp::create([
            'user_id' => $sarah->id,
            'event_id' => $eventOne->id,
            'extras' => 1,
        ]);

        Rsvp::create([
            'user_id' => $thando->id,
            'event_id' => $eventOne->id,
            'extras' => 2,
        ]);

        Rsvp::create([
            'user_id' => $lwazi->id,
            'event_id' => $eventOne->id,
            'extras' => 0,
        ]);

        Rsvp::create([
            'user_id' => $aphiwe->id,
            'event_id' => $eventTwo->id,
            'extras' => 1,
        ]);

        Rsvp::create([
            'user_id' => $kamo->id,
            'event_id' => $eventTwo->id,
            'extras' => 0,
        ]);

        Rsvp::create([
            'user_id' => $zanele->id,
            'event_id' => $eventThree->id,
            'extras' => 2,
        ]);

        Rsvp::create([
            'user_id' => $sarah->id,
            'event_id' => $eventThree->id,
            'extras' => 0,
        ]);

        // Maintain the calculated attendee value.
        foreach ($events as $event) {
            $totalAttendees = $event->rsvps()
                ->get()
                ->sum(function (Rsvp $rsvp): int {
                    return 1 + $rsvp->extras;
                });

            $event->update([
                'total_attendees' => $totalAttendees,
            ]);
        }
    }
}