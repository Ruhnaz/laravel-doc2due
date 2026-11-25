<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function (ViewContract $view): void {
            $user = auth()->user();

            if (! $user instanceof User) {
                return;
            }

            $company = $user->company()->with('plan')->first();
            $used = $company->documents()->count();
            $limit = (int) $company->plan->document_limit;
            $percent = $limit > 0 ? min(100, ($used / $limit) * 100) : ($used > 0 ? 100 : 0);

            $view->with([
                'sidebarPlanName' => $company->plan->name,
                'sidebarDocumentCount' => $used,
                'sidebarDocumentLimit' => $limit,
                'sidebarUsagePercent' => $percent,
                'sidebarUsageTone' => match (true) {
                    $percent >= 100 => 'bg-red-400',
                    $percent >= 80 => 'bg-amber-300',
                    default => 'bg-white/80',
                },
                'sidebarShowUpgrade' => $user->isAdmin() && ($percent >= 80 || $company->plan->name === 'Starter'),
            ]);
        });
    }
}
