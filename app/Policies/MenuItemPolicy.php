<?php

namespace App\Policies;

use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuItemPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->isMerchant() || $user->isAdmin();
    }

    public function update(User $user, MenuItem $menuItem): bool
    {
        return $this->owns($user, $menuItem);
    }

    public function delete(User $user, MenuItem $menuItem): bool
    {
        return $this->owns($user, $menuItem);
    }

    protected function owns(User $user, MenuItem $menuItem): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if (!$user->isMerchant()) {
            return false;
        }
        $menuItem->loadMissing('restaurant');
        
        return $menuItem->restaurant && $menuItem->restaurant->user_id === $user->id;
    }
}

