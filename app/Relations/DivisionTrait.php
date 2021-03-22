<?php

namespace App\Relations;

use App\Models\User;

trait DivisionTrait
{
   public function users()
   {
      return $this->hasMany(User::class, 'divisions_id');
   }
}
