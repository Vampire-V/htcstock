<?php

namespace App\Relations;

use App\Models\KPI\Rule;

trait KpiRuleTypeTrait
{
    public function rule()
    {
        return $this->hasOne(Rule::class)->withDefault();
    }
}
