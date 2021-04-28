<?php

namespace App\Http\Filters\KPI\Query;

class UserWhereIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('user_id', [...$value]);
    }
}
