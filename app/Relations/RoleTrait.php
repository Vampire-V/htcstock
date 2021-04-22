<?php

namespace App\Relations;

use App\Models\Permission;
use App\Models\User;

trait RoleTrait
{
    public function permissions()
    {

        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role')->with('roles')
        ->withPivot(['user_id','role_id']);
    }
}
