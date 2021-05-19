<?php

namespace App\Http\Filters\KPI\Query;

class RuleForDetail
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('rule', function ($query) use ($value) {
            return $query->where('id', $value);
        });
    }
}
