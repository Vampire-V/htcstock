<?php
namespace App\Http\Filters\KPI\Query;

class PeriodIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('period_id', [...$value]);
    }
}
