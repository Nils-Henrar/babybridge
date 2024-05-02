<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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

        //admin gate

        Gate::define('admin', function (User $user) {
            return $user->roles->contains('role', 'admin');
        });

        //worker gate

        Gate::define('worker', function (User $user) {
            return $user->roles->contains('role', 'worker');
        });

        //tutor gate

        Gate::define('tutor', function (User $user) {
            return $user->roles->contains('role', 'tutor');
        });
    }
}
