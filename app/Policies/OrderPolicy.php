<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BoltBite\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function before(?User $user, $ability)
    {
        if ($user && $user->isAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->id === $order->user_id) {
            return true;
        }

        if ($user->isMerchant() && $order->restaurant->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return !$user->isMerchant();
    }

    public function update(User $user, Order $order): bool
    {
        if ($user->isMerchant() && $order->restaurant->user_id === $user->id) {
            return true;
        }

        if ($user->id === $order->user_id && $order->status === 'pending') {
            return true;
        }

        return false;
    }

    public function delete(User $user, Order $order): bool
    {
        return false;
    }
}

