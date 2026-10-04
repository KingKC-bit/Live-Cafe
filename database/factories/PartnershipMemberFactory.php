<?php

namespace Database\Factories;

use App\Models\Partnership;
use App\Models\PartnershipMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PartnershipMember>
 */
class PartnershipMemberFactory extends Factory
{
    protected $model = PartnershipMember::class;

    public function definition(): array
    {
        return [
            'partnership_id' => Partnership::factory(),

            'user_id' => User::factory()->customer(),

            'employee_id' => fake()->unique()->numerify('SB-######'),

            'status' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => false,
        ]);
    }
}
