<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rsvp>
 */
class RsvpFactory extends Factory
{
    protected $model = Rsvp::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->customer(),

            'event_id' => Event::factory(),

            'extras' => fake()->numberBetween(0, 3),

            'status' => Rsvp::STATUS_GOING,
        ];
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => Rsvp::STATUS_CANCELLED,
            'cancelled_at' => now(),
        ]);
    }
}
