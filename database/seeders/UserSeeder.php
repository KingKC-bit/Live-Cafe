<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrator
        User::factory()
            ->admin()
            ->create([
                'name' => 'Lloyd',
                'surname' => 'Tshuketana',
                'email' => 'admin@livecafe.test',
                'phone_number' => '0821112233',
            ]);

        // Staff members
        User::factory()
            ->staff()
            ->create([
                'name' => 'Thabo',
                'surname' => 'Nkosi',
                'email' => 'staff1@livecafe.test',
                'phone_number' => '0832223344',
            ]);

        User::factory()
            ->staff()
            ->create([
                'name' => 'Naledi',
                'surname' => 'Dlamini',
                'email' => 'staff2@livecafe.test',
                'phone_number' => '0843334455',
            ]);

        // Customers
        $customers = [
            [
                'name' => 'Sarah',
                'surname' => 'Mokoena',
                'email' => 'sarah@livecafe.test',
                'phone_number' => '0711112233',
            ],
            [
                'name' => 'Thando',
                'surname' => 'Maseko',
                'email' => 'thando@livecafe.test',
                'phone_number' => '0722223344',
            ],
            [
                'name' => 'Lwazi',
                'surname' => 'Khumalo',
                'email' => 'lwazi@livecafe.test',
                'phone_number' => '0733334455',
            ],
            [
                'name' => 'Aphiwe',
                'surname' => 'Ndlovu',
                'email' => 'aphiwe@livecafe.test',
                'phone_number' => '0744445566',
            ],
            [
                'name' => 'Kamo',
                'surname' => 'Molefe',
                'email' => 'kamo@livecafe.test',
                'phone_number' => '0755556677',
            ],
            [
                'name' => 'Zanele',
                'surname' => 'Dube',
                'email' => 'zanele@livecafe.test',
                'phone_number' => '0766667788',
            ],
            [
                'name' => 'Musa',
                'surname' => 'Sithole',
                'email' => 'musa@livecafe.test',
                'phone_number' => '0777778899',
            ],
            [
                'name' => 'Keitumetse',
                'surname' => 'Molefe',
                'email' => 'keitumetse@livecafe.test',
                'phone_number' => '0788889900',
            ],
        ];

        foreach ($customers as $customer) {
            User::factory()
                ->customer()
                ->create($customer);
        }
    }
}