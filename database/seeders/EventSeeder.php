<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            'event_time' => '07:00',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
            'address' => 'Live Cafe, Sandton',
            'description' => 'Saturday community run followed by coffee and breakfast at Live Cafe.',
            'total_attendees' => 0,
        ]);

        Event::create([
            'event_time' => '06:30',
            'event_date' => now()->addDays(14)->format('Y-m-d'),
            'address' => 'Sandton Central',
            'description' => 'Social 5KM morning run for the Live Cafe running club.',
            'total_attendees' => 0,
        ]);

        Event::create([
            'event_time' => '06:00',
            'event_date' => now()->addDays(21)->format('Y-m-d'),
            'address' => 'George Lea Park',
            'description' => 'Longer weekend run followed by a group breakfast.',
            'total_attendees' => 0,
        ]);
    }
}