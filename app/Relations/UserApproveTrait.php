<?php

namespace App\Relations;

use App\Models\User;

trait UserApproveTrait
{
    
    public function approveBy()
    {
        return $this->belongsTo(User::class,'user_approve')->withDefault();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by')->withDefault();
    }

    public function modifyBy()
    {
        return $this->belongsTo(User::class,'modify_by')->withDefault();
    }
}
