<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\SaleSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

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
        ]);
    }
}