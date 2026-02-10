<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Les politiques (policies) de l'application.
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Enregistrez les services d'authentification / d'autorisation.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // 1. Accès complet à l'administration
        Gate::define('admin-access', function (User $user) {
            return $user->role === 'admin';
        });

        // 2. Gestion des courriers (Admin ou Secrétaire)
        Gate::define('manage-courriers', function (User $user) {
            return in_array($user->role, ['admin', 'secretaire']);
        });

        // 3. Validation des présences (RH ou Chef)
        Gate::define('validate-presences', function (User $user) {
            return in_array($user->role, ['admin', 'rh', 'chef_service']);
        });

        // 4. Consultation des statistiques avancées
        Gate::define('view-stats', function (User $user) {
            return in_array($user->role, ['admin', 'directeur']);
        });
    }
}
