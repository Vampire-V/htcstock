<?php

namespace App\Relations;

use App\Models\Legal\LegalComercialTerm;
use App\Models\Legal\LegalContract;
use App\Models\Legal\LegalPaymentTerm;
use App\Models\Legal\LegalPaymentType;
use App\Models\Legal\LegalSubtypeContract;

trait LegalContractDestTrait
{
    public function legalcontract()
    {
        return $this->belongsTo(LegalContract::class, 'contract_id');
    }

    public function legalComercialTerm()
    {
        return $this->hasOne(LegalComercialTerm::class,'contract_dest_id');
    }

    public function legalPaymentTerm()
    {
        return $this->belongsTo(LegalPaymentTerm::class, 'payment_term_id')->withDefault();
    }

    public function legalPaymentType()
    {
        return $this->belongsTo(LegalPaymentType::class, 'payment_type_id')->withDefault();
    }

    public function legalSubtypeContract()
    {
        return $this->belongsTo(LegalSubtypeContract::class, 'sub_type_contract_id')->withDefault();
    }
}
