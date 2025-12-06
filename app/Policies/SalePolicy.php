<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Sale;
class SalePolicy
{
    /**
     * Create a new policy instance.
     */

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'vendedor';
    }
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'vendedor';
    }
}
