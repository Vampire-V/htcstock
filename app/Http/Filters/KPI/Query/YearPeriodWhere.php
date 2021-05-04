<?php

namespace App\Http\Filters\KPI\Query;

class YearPeriodWhere
{
    public function filter($builder, $value)
    {
        return $builder->where('year', $value);
    }
}
