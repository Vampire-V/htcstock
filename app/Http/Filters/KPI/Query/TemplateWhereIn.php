<?php

namespace App\Http\Filters\KPI\Query;

class TemplateWhereIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('id', [...$value]);
    }
}
