<?php

namespace App\Relations;

use App\Models\KPI\Rule;

trait RuleCategoryTrait
{
    public function rule()
    {
        return $this->hasMany(Rule::class, 'category_id');
    }
}
