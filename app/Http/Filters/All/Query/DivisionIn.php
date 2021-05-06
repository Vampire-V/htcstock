<?php
namespace App\Http\Filters\All\Query;

class DivisionIn
{
    public function filter($builder, $value)
    {
        return $builder->orWhereIn('divisions_id',[...$value]);
    }
}
