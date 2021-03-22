<?php

namespace App\Relations;

use App\Models\Legal\LegalContractDest;

trait LegalPaymentTypeTrait
{
    public function legalContractDest()
    {
        return $this->hasOne(LegalContractDest::class, 'payment_type_id');
    }
}
