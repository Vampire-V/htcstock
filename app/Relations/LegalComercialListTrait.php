<?php

namespace App\Relations;

use App\Models\Legal\LegalContractDest;

trait LegalComercialListTrait
{
    public function legalContractDest()
    {
        return $this->belongsTo(LegalContractDest::class, 'contract_dests_id')->withDefault();
    }
}
