<?php

namespace App\Http\Filters\KPI\Query;

class QuarterPeriodWhereHas
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('targetperiod', fn ($query) => $query->whereIn('quarter', [...$value]));
    }
}
