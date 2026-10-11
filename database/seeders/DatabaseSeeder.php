<?php

namespace Database\Seeders;

<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

<<<<<<< HEAD
    public function run(): void
    {
        $this->call([
            UserSeeder::class,

            CategorySeeder::class,

            IngredientSeeder::class,

            ProductSeeder::class,

            ImageSeeder::class,

            AnnouncementSeeder::class,

            EventSeeder::class,

            RsvpSeeder::class,

            PartnershipSeeder::class,

            PartnershipMemberSeeder::class,

            OrderSeeder::class,

            SaleSeeder::class,
=======
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3
        ]);
    }
}
