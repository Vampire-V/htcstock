<?php

namespace App\Http\Filters\KPI\Query;

class UserForDetail
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('evaluate', fn ($query) => $query->where('user_id', $value));
    }
}
