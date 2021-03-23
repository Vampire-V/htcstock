<?php
namespace App\Http\Filters\KPI\Query;

class YearPeriodIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('year', [...$value]);
    }
}
