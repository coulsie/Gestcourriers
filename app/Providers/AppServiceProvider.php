<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

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
    // FORCE LE PASSAGE POUR TOUT LE MONDE (Test uniquement)
    Gate::before(function ($user, $ability) {
        return true;
    });

    // Gardez vos définitions en dessous si vous voulez
    Gate::define('voir-utilisateurs', function (User $user) {
        return true;
    });

     Paginator::useBootstrapFive();
}






}
