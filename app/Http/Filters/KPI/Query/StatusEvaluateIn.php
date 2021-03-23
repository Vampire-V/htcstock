<?php
namespace App\Http\Filters\KPI\Query;

class StatusEvaluateIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('status', [...$value]);
    }
}
