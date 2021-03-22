<?php

namespace App\Relations;

use App\Models\Legal\LegalContract;

trait LegalActionTrait
{
   public function legalcontract()
   {
      return $this->hasMany(LegalContract::class);
   }
}
