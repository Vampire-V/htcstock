<?php
namespace App\Http\Filters\All\Query;

class UserIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('id',[...$value]);
    }
}
