<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Models\BoltBite\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function before(?User $user, $ability)
    {
        if ($user && $user->isAdmin()) {
            return true;
        }
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Comment $comment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        if ($user->isMerchant()) {
            return false;
        }

        return true;
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    public function createForOrder(User $user, Order $order): bool
    {
        if ($user->isMerchant()) {
            return false;
        }

        if ($user->id !== $order->user_id) {
            return false;
        }

        if ($order->status !== 'delivered') {
            return false;
        }

        if ($order->comments()->where('user_id', $user->id)->exists()) {
            return false;
        }

        return true;
    }
}

