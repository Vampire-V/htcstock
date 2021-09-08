<?php

namespace App\Relations;

use App\Models\Legal\LegalContractDest;

trait LegalComercialTermTrait
{
    public function legalContractDest()
    {
        return $this->belongsTo(LegalContractDest::class, 'contract_dest_id');
    }
}
