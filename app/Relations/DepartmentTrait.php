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

   public function operation()
    {
        return $this->belongsToMany(User::class, 'kpi_operation_dept')->with('department')
        ->withPivot(['user_id','department_id']);
    }
}
