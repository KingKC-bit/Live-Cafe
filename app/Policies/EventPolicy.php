<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Event $event): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Event $event): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->isAdmin();
    }

    /**
     * Any authenticated user may RSVP.
     *
     * The eight-hour deadline will be enforced
     * by the RSVP service/action, not this policy.
     */
    public function rsvp(User $user, Event $event): bool
    {
        return true;
    }
}
