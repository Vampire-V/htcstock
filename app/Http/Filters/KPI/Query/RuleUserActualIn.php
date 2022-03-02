<?php

namespace App\Http\Filters\KPI\Query;

class RuleUserActualIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('user_actual',[...$value]);
    }
}
