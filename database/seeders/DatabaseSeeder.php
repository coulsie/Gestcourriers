<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. CRÉATION DES PERMISSIONS (Toutes celles nécessaires au projet)
        $p1 = Permission::firstOrCreate(['name' => 'voir-utilisateurs']);
        $p2 = Permission::firstOrCreate(['name' => 'manage-users']);
        $p3 = Permission::firstOrCreate(['name' => 'creer-articles']);
        $p4 = Permission::firstOrCreate(['name' => 'supprimer-articles']);

        // 2. CRÉATION DES RÔLES
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'utilisateur']);

        // 3. ATTRIBUTION DES PERMISSIONS AUX RÔLES
        // L'admin reçoit TOUTES les permissions
        $adminRole->syncPermissions([$p1, $p2, $p3, $p4]);

        // L'utilisateur simple reçoit seulement le droit de voir et de créer des articles
        $userRole->syncPermissions([$p1, $p3]);


        // 4. CRÉATION OU MISE À JOUR DE L'ADMINISTRATEUR
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin Test',
                'password' => bcrypt('password'), // Le mot de passe reste 'password'
            ]
        );
        // On synchronise le rôle (évite les doublons)
        $admin->syncRoles([$adminRole]);


        // 5. CRÉATION OU MISE À JOUR DE L'UTILISATEUR SIMPLE
        $simpleUser = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'     => 'Utilisateur Test',
                'password' => bcrypt('password'),
            ]
        );
        $simpleUser->syncRoles([$userRole]);

        $this->command->info('Sécurité initialisée : Permissions et Rôles sont à jour !');
    }
}
