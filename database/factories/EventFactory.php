<?php

namespace Database\Factories;

use App\Models\Event;
use Carbon\CarbonInterface;
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
            'title' => fake()->randomElement(['Saturday Run', 'Sunrise 5K', 'Long Run', 'Social Run']),

            'type' => Event::TYPE_RUN,

            'event_time' => fake()->randomElement(['06:00', '06:30', '07:00', '17:30']),

            'event_date' => fake()->dateTimeBetween('+2 days', '+60 days')->format('Y-m-d'),

            'address' => fake()->randomElement([
                '102 Rivonia Road, Sandton',
                'Sandton Central',
                'George Lea Park',
            ]),

            'distance_km' => fake()->randomElement([5, 10, 21.1]),

            'description' => fake()->paragraph(2),
        ];
    }

    /**
     * Start at an exact moment, for tests around the eight-hour cutoff.
     */
    public function startingAt(CarbonInterface $startsAt): static
    {
        return $this->state(fn () => [
            'event_date' => $startsAt->format('Y-m-d'),
            'event_time' => $startsAt->format('H:i'),
        ]);
    }

    /**
     * A non-run event, such as a brand-sponsored activation.
     */
    public function event(): static
    {
        return $this->state(fn () => [
            'type' => Event::TYPE_EVENT,
            'distance_km' => null,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => Event::STATUS_CANCELLED,
            'cancelled_at' => now(),
        ]);
    }
}
