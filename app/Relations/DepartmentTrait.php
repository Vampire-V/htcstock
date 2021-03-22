<?php

namespace App\Relations;

use App\Models\KPI\Template;
use App\Models\Legal\LegalApproval;
use App\Models\User;

trait DepartmentTrait
{
   // ITSTOCK
   public function users()
   {
      return $this->hasMany(User::class, 'department_id');
   }

   // Legal
   public function legalApprove()
   {
      return $this->hasMany(LegalApproval::class);
   }

   // KPI
   public function template()
   {
      return $this->hasOne(Template::class)->withDefault();
   }
}
