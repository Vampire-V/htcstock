<?php

namespace App\Http\Filters\KPI\Query;

class RuleTypeIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('kpi_rule_types_id',[...$value]);
    }
}
