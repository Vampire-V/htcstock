<?php

namespace App\Http\Filters\IT\Query;

class UserWhereIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('trans_by', [...$value]);
    }
}
