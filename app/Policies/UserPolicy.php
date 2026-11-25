<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $employee): bool
    {
        return $this->manages($user, $employee);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, User $employee): bool
    {
        return $this->manages($user, $employee);
    }

    public function delete(User $user, User $employee): bool
    {
        return $this->manages($user, $employee) && $user->isNot($employee);
    }

    private function manages(User $user, User $employee): bool
    {
        return $user->isAdmin() && $user->company_id === $employee->company_id;
    }
}
