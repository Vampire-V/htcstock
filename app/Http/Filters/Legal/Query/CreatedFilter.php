<?php
namespace App\Http\Filters\Legal\Query;

class CreatedFilter
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('created_by', [...$value]);
    }
}
