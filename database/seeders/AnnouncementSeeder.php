<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@livecafe.test')->first();

        $announcements = [
            [
                'title' => 'Black and pink this Saturday',
                'description' => 'The dress code for Saturday\'s 5 km is black and pink. We start at 07:00 from 102 Rivonia Road.',
                'is_pinned' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'RSVP so we can plan',
                'description' => 'Tap RSVP on a run so we know roughly how many people to expect. RSVPs close 8 hours before each start.',
                'is_pinned' => false,
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Club merch is on the way',
                'description' => 'Live Running Club merch will be available at the café soon.',
                'is_pinned' => false,
                'published_at' => null, // still a draft
            ],
        ];

        foreach ($announcements as $attributes) {
            $announcement = new Announcement;
            $announcement->forceFill($attributes + ['user_id' => $admin?->id])->save();
        }
    }
}
