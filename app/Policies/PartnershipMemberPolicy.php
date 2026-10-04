<?php

namespace App\Policies;

use App\Models\PartnershipMember;
use App\Models\User;

class PartnershipMemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, PartnershipMember $member): bool
    {
        return $user->isAdmin()
            || $member->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, PartnershipMember $member): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, PartnershipMember $member): bool
    {
        return $user->isAdmin();
    }
}
