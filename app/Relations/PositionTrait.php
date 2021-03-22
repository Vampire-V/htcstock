<?php

namespace App\Relations;

use App\Models\User;

trait PositionTrait
{
    public function users()
    {
        return $this->hasMany(User::class, 'posisions_id');
    }
}
