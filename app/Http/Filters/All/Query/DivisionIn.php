<?php
namespace App\Http\Filters\All\Query;

class DivisionIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('divisions_id',[...$value]);
    }
}
