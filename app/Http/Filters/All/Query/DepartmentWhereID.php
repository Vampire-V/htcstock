<?php

namespace App\Http\Filters\All\Query;

class DepartmentWhereID
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('department_id',[...$value]);
    }
}
