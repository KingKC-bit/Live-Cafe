<?php

namespace Database\Seeders;

use App\Models\Partnership;
use App\Models\PartnershipMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class PartnershipMemberSeeder extends Seeder
{
    public function run(): void
    {
        $partnership = Partnership::where(
            'name',
            'Standard Bank'
        )->firstOrFail();

        $members = [
            [
                'email' => 'aphiwe@livecafe.test',
                'employee_id' => 'SB-100245',
            ],
            [
                'email' => 'kamo@livecafe.test',
                'employee_id' => 'SB-100389',
            ],
            [
                'email' => 'zanele@livecafe.test',
                'employee_id' => 'SB-100472',
            ],
        ];

        foreach ($members as $member) {
            $user = User::where(
                'email',
                $member['email']
            )->firstOrFail();

            PartnershipMember::create([
                'partnership_id' => $partnership->id,
                'user_id' => $user->id,
                'employee_id' => $member['employee_id'],
                'status' => true,
            ]);
        }
    }
}
