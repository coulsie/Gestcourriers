<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

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

    
    // Votre définition de Gate
        Gate::define('manage-users', function (User $user) {
            // Exemple : vérifie si l'utilisateur a le rôle admin
            return $user->role === 'admin';
        });

        Gate::define('voir-utilisateurs', function (User $user) {
            return $user->role === 'admin' || $user->role === 'rh';
        });
    }

}
