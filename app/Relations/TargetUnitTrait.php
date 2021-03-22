<?php

namespace App\Relations;

use App\Models\KPI\Rule;

trait TargetUnitTrait
{
    public function rule()
    {
        return $this->hasOne(Rule::class)->withDefault();
    }
}
