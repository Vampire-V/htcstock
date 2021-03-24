<?php

namespace App\Http\Filters\KPI\Query;

class DepartmentWhereHas
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('user', function ($query) use ($value) {
            return $query->whereIn('department_id', [...$value]);
        });
    }
}
