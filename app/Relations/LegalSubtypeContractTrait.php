<?php

namespace App\Relations;

use App\Models\Legal\LegalAgreement;
use App\Models\Legal\LegalContractDest;

trait LegalSubtypeContractTrait
{
    public function legalContractDest()
    {
        return $this->hasOne(LegalContractDest::class);
    }

    public function agreement()
    {
        return $this->belongTo(LegalAgreement::class, 'agreement_id');
    }
}
