<?php

namespace Database\Seeders;

use App\Models\Partnership;
use Illuminate\Database\Seeder;

class PartnershipSeeder extends Seeder
{
    public function run(): void
    {
        Partnership::create([
            'name' => 'Standard Bank',
            'description' => 'Standard Bank employee partnership providing eligible employees with the agreed Live Cafe benefit.',
            'status' => true,
        ]);
    }
}