<?php

namespace App\Http\Filters\KPI\Query;

class MonthPeriodWhereHas
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('targetperiod', fn ($query) => $query->whereIn('name', [...$value]));
    }
}
