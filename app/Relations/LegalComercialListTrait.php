<?php

namespace App\Relations;

use App\Models\Legal\LegalContract;

trait LegalComercialListTrait
{
    public function legalContract()
    {
        return $this->belongsTo(LegalContract::class)->withDefault();
    }
}
