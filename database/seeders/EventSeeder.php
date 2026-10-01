<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Event;
use Carbon\CarbonInterface;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Dates are worked out from the day you seed, so the demo always has
        // an upcoming Saturday run (and last Saturday's run in the history).
        $nextSaturday = now()->next(CarbonInterface::SATURDAY);
        $lastSaturday = now()->previous(CarbonInterface::SATURDAY);

        // The club's Saturday run.
        $saturdayRun = Event::create([
            'title' => 'Saturday Run',
            'type' => Event::TYPE_RUN,
            'event_date' => $nextSaturday->format('Y-m-d'),
            'event_time' => '07:00',
            'address' => '102 Rivonia Road, Sandton',
            'distance_km' => 5,
            'dress_code' => 'Black and pink',
            'description' => 'Our weekly Saturday 5 km from 102 Rivonia Road. All paces welcome.',
        ]);

        // The pinned announcement about this run's dress code links to it, so
        // members can open the run and RSVP from the announcement.
        Announcement::query()
            ->where('title', 'Black and pink this Saturday')
            ->update(['event_id' => $saturdayRun->id]);

        // Last week's run, so the admin page has some history.
        Event::create([
            'title' => 'Saturday Run',
            'type' => Event::TYPE_RUN,
            'event_date' => $lastSaturday->format('Y-m-d'),
            'event_time' => '07:00',
            'address' => '102 Rivonia Road, Sandton',
            'distance_km' => 5,
            'description' => 'Our weekly community 5 km.',
        ]);
    }
}
