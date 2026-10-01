<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Staff and administrators can view all orders.
     */
    public function viewAny(User $user): bool
    {
        return $user->isStaffOrAdmin();
    }

    /**
     * A customer may view their own order.
     * Staff and admins may view any order.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->isStaffOrAdmin()
            || $order->user_id === $user->id;
    }

    /**
     * Authenticated customers can create orders.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Customers can only modify their own orders.
     * Staff/admin can modify any order.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->isStaffOrAdmin()
            || $order->user_id === $user->id;
    }

    /**
     * Staff/admin control cancellation at the system level.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }
}
