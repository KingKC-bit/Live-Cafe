<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'event_time' => fake()->time('H:i'),

            'event_date' => fake()->dateTimeBetween(
                'now',
                '+60 days'
            )->format('Y-m-d'),

            'address' => fake()->randomElement([
                'Live Cafe, Sandton',
                'Sandton Central',
                'George Lea Park',
                'Sandton City Park',
            ]),

            'description' => fake()->paragraph(2),

            'total_attendees' => 0,
        ];
    }
}
