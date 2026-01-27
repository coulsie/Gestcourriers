<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Affiche la liste des rôles avec leurs permissions.
     */
    public function index()
    {
        // On charge les rôles et on compte leurs permissions
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Formulaire pour modifier un rôle et ses permissions.
     */
    public function create()
    {
        $permissions = \Spatie\Permission\Models\Permission::all();
        return view('roles.create', compact('permissions'));
    }


    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Changez le nom ici pour correspondre à votre vue
        $permissions = Permission::all();

        // Récupérer les IDs des permissions déjà possédées par ce rôle
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        // Utilisez 'permissions' au lieu de 'allPermissions'
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Met à jour le rôle et synchronise les permissions dans la table croisée.
     */
 public function update(Request $request, $id)
{
    $role = \App\Models\Role::findOrFail($id);

    // 1. On récupère les NOMS des permissions cochées via leurs IDs
    $permissionNames = \App\Models\Permission::whereIn('id', $request->input('permissions', []))
                        ->pluck('name')
                        ->toArray();

    // 2. Synchronisation par noms (beaucoup plus fiable avec Spatie)
    $role->syncPermissions($permissionNames);

    // 3. Nettoyage manuel du cache pour forcer Laravel à voir les changements
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    return redirect()->route('roles.index')->with('success', 'Permissions mises à jour !');
}


        public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'nullable|array'
        ]);

        // Création du rôle avec le guard par défaut de Laravel
        $role = \Spatie\Permission\Models\Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        // Si des permissions ont été cochées dans le create.blade.php
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Nouveau rôle ajouté avec succès !');
    }

}
