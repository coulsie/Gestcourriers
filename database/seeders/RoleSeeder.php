<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Nettoyer le cache des permissions (recommandé par Spatie)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Liste des permissions (Ajoutez-en autant que vous voulez ici)
        $permissions = [
            'modifier articles',
            'supprimer articles',
            'voir-utilisateurs',
            'gerer-roles',
            'acceder-dashboard'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Créer les rôles et attribuer les permissions

        // ADMIN : possède TOUT
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // EDITEUR : modifier + dashboard
        $editor = Role::firstOrCreate(['name' => 'editeur']);
        $editor->syncPermissions(['modifier articles', 'acceder-dashboard']);

        // RH : voir-utilisateurs + dashboard
        $rh = Role::firstOrCreate(['name' => 'rh']);
        $rh->syncPermissions(['voir-utilisateurs', 'acceder-dashboard']);
    }
}
