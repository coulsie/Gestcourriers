<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends SpatieRole
{
    // Indiquez le nom de la table si elle n'est pas "roles" au pluriel
    protected $table = 'roles';

    // Relation avec les utilisateurs
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    // Relation avec vos permissions (via votre table role_has_permissions)
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

}
namespace App\Models;




