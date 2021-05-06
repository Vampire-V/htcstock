<?php

namespace App\Http\Filters\All\Query;

class DepartmentIn
{
    public function filter($builder, $value)
    {
        return $builder->orWhereIn('department_id',[...$value]);
    }
}
