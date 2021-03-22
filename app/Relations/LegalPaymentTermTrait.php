<?php

namespace App\Relations;

use App\Models\Legal\LegalContractDest;

trait LegalPaymentTermTrait
{
    public function legalContractDest()
    {
        return $this->hasOne(LegalContractDest::class, 'payment_term_id');
    }
}
