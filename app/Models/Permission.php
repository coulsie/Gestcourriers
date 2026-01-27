<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as SpatiePermission;


class Permission extends SpatiePermission
{
    // Indiquez explicitement la table si elle ne suit pas la convention plurielle
    protected $table = 'permissions';

    public function roles(): BelongsToMany
    {
        // On précise la table pivot 'role_has_permissions'
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }
}
namespace App\Models;


