<?php

namespace App\Http\Filters\KPI\Query;

class DeptWhereInForTem
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('department_id',[...$value]);
    }
}
