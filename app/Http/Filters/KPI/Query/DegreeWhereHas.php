<?php

namespace App\Http\Filters\KPI\Query;

class DegreeWhereHas
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('user', fn ($query) => $query->whereIn('degree', [...$value]));
    }
}
