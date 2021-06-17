<?php

namespace App\Relations;

use App\Models\User;

trait SettingActionTrait
{
    public function users()
    {
        return $this->belongsToMany(User::class,'kpi_setting_action_user');
    }
}
