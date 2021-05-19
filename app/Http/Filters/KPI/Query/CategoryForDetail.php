<?php

namespace App\Http\Filters\KPI\Query;

class CategoryForDetail
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('rule', function ($query) use ($value) {
            return $query->where('category_id', $value);
        });
    }
}
