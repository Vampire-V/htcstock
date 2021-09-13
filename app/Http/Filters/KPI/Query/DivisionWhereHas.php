<?php

namespace App\Http\Filters\KPI\Query;

class DivisionWhereHas
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('user', function ($query) use ($value) {
            return $query->whereIn('divisions_id', [...$value]);
        });
    }
}
