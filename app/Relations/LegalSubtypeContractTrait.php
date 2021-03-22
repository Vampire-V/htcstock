<?php

namespace App\Relations;

use App\Models\Legal\LegalContractDest;

trait LegalSubtypeContractTrait
{
    public function legalContractDest()
    {
        return $this->hasOne(LegalContractDest::class);
    }
}
