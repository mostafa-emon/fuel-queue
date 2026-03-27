<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });

        \Illuminate\Support\Facades\Gate::define('isOperator', function ($user) {
            return $user->role === 'operator';
        });

        \Illuminate\Support\Facades\Gate::define('isUser', function ($user) {
            return $user->role === 'user';
        });
    }
}
