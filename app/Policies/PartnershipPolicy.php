<?php

namespace App\Policies;

use App\Models\Partnership;
use App\Models\User;

class PartnershipPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Partnership $partnership): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Partnership $partnership): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Partnership $partnership): bool
    {
        return $user->isAdmin();
    }
}