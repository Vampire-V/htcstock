<?php

namespace App\Relations;

use App\Models\Legal\LegalAgreement;
use App\Models\Legal\LegalContractDest;
use App\Models\Legal\LegalPaymentType;

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

    public function payment_types()
    {
        return $this->hasMany(LegalPaymentType::class,'subtype_id');
    }
}
