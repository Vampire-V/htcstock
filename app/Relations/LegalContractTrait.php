<?php

namespace App\Relations;

use App\Models\Legal\LegalAction;
use App\Models\Legal\LegalAgreement;
use App\Models\Legal\LegalApprovalDetail;
use App\Models\Legal\LegalComercialList;
use App\Models\Legal\LegalContractDest;
use App\Models\User;

trait LegalContractTrait
{
   public function legalAction()
   {
      return $this->belongsTo(LegalAction::class, 'action_id')->withDefault();
   }

   public function legalAgreement()
   {
      return $this->belongsTo(LegalAgreement::class, 'agreement_id')->withDefault();
   }

   public function legalContractDest()
   {
      return $this->hasOne(LegalContractDest::class, 'contract_id');
   }

   public function requestorBy()
   {
      return $this->belongsTo(User::class, 'requestor_by')->withDefault();
   }

   public function checkedBy()
   {
      return $this->belongsTo(User::class, 'checked_by')->withDefault();
   }

   public function createdBy()
   {
      return $this->belongsTo(User::class, 'created_by')->withDefault();
   }

   public function approvalDetail()
   {
      return $this->hasMany(LegalApprovalDetail::class,'contract_id');
   }

   public function legalComercialList()
    {
        return $this->hasMany(LegalComercialList::class, 'contract_id');
    }

}
