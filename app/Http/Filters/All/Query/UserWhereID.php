<?php
namespace App\Http\Filters\All\Query;

class UserWhereID
{
    public function filter($builder, $value)
    {
        return $builder->WhereIn('id',[...$value]);
    }
}
