<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewBilling(User $user, Company $company): bool
    {
        return $user->isAdmin() && $user->company_id === $company->id;
    }

    public function updatePlan(User $user, Company $company): bool
    {
        return $this->viewBilling($user, $company);
    }
}
