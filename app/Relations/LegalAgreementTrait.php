<?php

namespace App\Relations;

use App\Models\Legal\LegalContract;
use App\Models\Legal\LegalSubtypeContract;

trait LegalAgreementTrait
{
   public function legalcontract()
   {
      return $this->hasMany(LegalContract::class);
   }

   public function subTypeContract()
   {
      return $this->hasMany(LegalSubtypeContract::class, 'agreement_id');
   }
}
