<?php

namespace App\Relations;

use App\Models\Legal\LegalContract;

trait LegalAgreementTrait
{
   public function legalcontract()
   {
      return $this->hasMany(LegalContract::class);
   }
}
