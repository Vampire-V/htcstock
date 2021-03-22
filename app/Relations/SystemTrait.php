<?php

namespace App\Relations;

use App\Models\User;

trait SystemTrait
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
