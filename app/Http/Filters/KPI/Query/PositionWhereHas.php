<?php

namespace App\Http\Filters\KPI\Query;

class PositionWhereHas
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('user', function ($query) use ($value) {
            return $query->whereIn('positions_id', [...$value]);
        });
    }
}
