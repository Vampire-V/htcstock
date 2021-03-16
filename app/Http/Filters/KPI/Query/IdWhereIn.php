<?php

namespace App\Http\Filters\KPI\Query;

class IdWhereIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('id',[...$value]);
    }
}
