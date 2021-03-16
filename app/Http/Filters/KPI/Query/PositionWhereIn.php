<?php

namespace App\Http\Filters\KPI\Query;

class PositionWhereIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('id',[...$value]);
    }
}
