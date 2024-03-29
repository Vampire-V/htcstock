<?php

namespace App\Relations;

use App\Models\Role;
use App\Models\System;
use App\Models\User;

trait PermissionTrait
{
    public function roles()
    {
       return $this->belongsToMany(Role::class, 'role_permission', 'permission_id');
    }
 
    public function users()
    {
       return $this->belongsToMany(User::class, 'user_permission');
    }

    public function system()
    {
       return $this->belongsTo(System::class)->withDefault();
    }
}
