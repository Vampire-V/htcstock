<?php

namespace App\Relations;

use App\Models\Legal\LegalContract;
use App\Models\User;

trait LegalApprovalDetailTrait
{
   public function user()
   {
      return $this->belongsTo(User::class, 'user_id')->withDefault();
   }

   public function contract()
   {
      return $this->belongsTo(LegalContract::class, 'contract_id')->withDefault();
   }
}
