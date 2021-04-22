<?php

namespace App\Http\Filters\KPI\Query;

class YearPeriodForDetail
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('evaluate', function ($query) use ($value) {
            return $query->whereHas('targetperiod', fn ($q) => $q->where('year', $value));
        });
    }
}
