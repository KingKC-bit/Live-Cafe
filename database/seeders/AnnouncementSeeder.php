<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        Announcement::create([
            'description' => 'Saturday community run starts at 07:00 from Live Cafe. New runners are welcome.',
        ]);

        Announcement::create([
            'description' => 'Live Cafe running club merchandise is now available for collection.',
        ]);

        Announcement::create([
            'description' => 'Please RSVP for upcoming running events so that the team can prepare for expected attendance.',
        ]);
    }
}
