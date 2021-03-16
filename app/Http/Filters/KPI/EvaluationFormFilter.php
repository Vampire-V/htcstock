<?php

namespace App\Http\Filters\KPI;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\KPI\Query\IdWhereIn;

class EvaluationFormFilter extends AbstractFilter
{
    protected $filters = [
        'position_id' => IdWhereIn::class,
        'department_id' => IdWhereIn::class
    ];
}
