<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),

            'surname' => fake()->lastName(),

            'email' => fake()->unique()->safeEmail(),

            'email_verified_at' => now(),

            'password' => static::$password ??= Hash::make('password'),

            'role' => 'customer',

            'phone_number' => fake()->numerify('0#########'),

            'remember_token' => Str::random(10),
        ];
    }

    public function customer(): static
    {
        return $this->state(fn () => [
            'role' => 'customer',
        ]);
    }

    public function staff(): static
    {
        return $this->state(fn () => [
            'role' => 'staff',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => 'admin',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }
}
