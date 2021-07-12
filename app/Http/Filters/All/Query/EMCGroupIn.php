<?php
namespace App\Http\Filters\All\Query;

class EMCGroupIn
{
    public function filter($builder, $value)
    {
        return $builder->WhereIn('degree',[...$value]);
    }
}
