<?php

namespace App\Http\Filters\KPI\Query;

class YearPeriodWhereHas
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('targetperiod', fn ($query) => $query->whereIn('year', [...$value]));
    }
}
