<?php

namespace App\Relations;

use App\Models\Legal\LegalContract;
use App\Models\Legal\LegalSubtypeContract;
use App\Models\Legal\TemplateLibary;

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

   public function template_libarys()
   {
      return $this->hasMany(TemplateLibary::class, 'agreement_id')->orderBy('created_at','DESC');
   }
}
