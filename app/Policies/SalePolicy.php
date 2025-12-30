<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Sale;
class SalePolicy
{
    //Policys para que el admin y el vendedor puedan ver y crear ventas

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'employee';
    }
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'employee';
    }
}
