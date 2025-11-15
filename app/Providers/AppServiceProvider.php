<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;
use App\Observers\CardObserver;

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
        // Register Model Observers
        Card::observe(CardObserver::class);

        // Define Gates for authorization
        Gate::define('manage-projects', function ($user) {
            return in_array($user->role, ['Project_Admin', 'Team_Lead']);
        });

        Gate::define('manage-users', function ($user) {
            return $user->role === 'Project_Admin';
        });

        Gate::define('admin-only', function ($user) {
            return $user->role === 'Project_Admin';
        });

        Gate::define('team-lead-or-admin', function ($user) {
            return in_array($user->role, ['Project_Admin', 'Team_Lead']);
        });
    }
}
