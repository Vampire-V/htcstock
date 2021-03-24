<?php

namespace App\Http\Filters\KPI\Query;

class YearPeriodWhereHas
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('targetperiod', function ($query) use ($value) {
            return $query->whereIn('year', [...$value]);
        });
    }
}
