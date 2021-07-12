<?php

namespace App\Http\Filters\All\Query;

class DepartmentWhereID
{
    public function filter($builder, $value)
    {
        return $builder->WhereIn('department_id',[...$value]);
    }
}
