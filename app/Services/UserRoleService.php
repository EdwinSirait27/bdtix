<?php

namespace App\Services;
use App\Models\User;

class UserRoleService
{
    public function updateRoles(User $user, array $roles = [])
    {
        $user->syncRoles($roles);
    }
}