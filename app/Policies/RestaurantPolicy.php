<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RestaurantPolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Restaurant $restaurant): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isMerchant() && $restaurant->user_id === $user->id;
    }
}

