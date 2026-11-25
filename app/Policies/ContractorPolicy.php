<?php

namespace App\Policies;

use App\Models\Contractor;
use App\Models\User;

class ContractorPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Contractor $contractor): bool
    {
        return $user->company_id === $contractor->company_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Contractor $contractor): bool
    {
        return $this->view($user, $contractor);
    }

    public function delete(User $user, Contractor $contractor): bool
    {
        return $this->view($user, $contractor);
    }
}
