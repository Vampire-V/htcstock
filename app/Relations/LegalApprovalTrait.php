<?php

namespace App\Relations;

use App\Models\Department;
use App\Models\User;

trait LegalApprovalTrait
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'contract_id')->withDefault();
    }
}
