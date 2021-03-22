<?php

namespace App\Relations;

use App\Models\User;

trait SystemTrait
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'system_id')->withDefault();
    }
}
