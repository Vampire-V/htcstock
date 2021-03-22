<?php

namespace App\Relations;

use App\Models\Legal\LegalContractDest;

trait LegalComercialTermTrait
{
    public function legalContractDest()
    {
        return $this->hasOne(LegalContractDest::class, 'comercial_term_id');
    }
}
