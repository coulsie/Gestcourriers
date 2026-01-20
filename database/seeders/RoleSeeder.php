<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer les permissions
        Permission::create(['name' => 'modifier articles']);
        Permission::create(['name' => 'supprimer articles']);

        // 2. Créer les rôles et leur attribuer des permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $editor = Role::create(['name' => 'editeur']);
        $editor->givePermissionTo('modifier articles');
    }
}
