<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CompanyScope implements Scope
{
    /**
     * Limit tenant-owned rows to the signed-in user's company.
     *
     * Guests are not filtered, so login and registration can still find users.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! Auth::hasUser()) {
            return;
        }

        $companyId = Auth::user()->company_id;

        if ($companyId) {
            $builder->where($model->qualifyColumn('company_id'), $companyId);
        }
    }
}
