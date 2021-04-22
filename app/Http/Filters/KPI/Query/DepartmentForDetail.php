<?php

namespace App\Http\Filters\KPI\Query;

class DepartmentForDetail
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('evaluate', function ($query) use ($value) {
            return $query->whereHas('user', fn ($q) => $q->where('department_id', $value));
        });
    }
}
