<?php

namespace App\Http\Filters\All\Query;

class PositionIn
{
    public function filter($builder, $value)
    {
        return $builder->whereIn('positions_id',[...$value]);
    }
}
