<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
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
        Blade::if('role', function ($roles) {
            if (!Auth::check()) {
                return false;
            }

            $roles = is_array($roles) ? $roles : explode(',', $roles);

            return in_array(trim(Auth::user()->role->name), array_map('trim', $roles));
        });
    }
}
