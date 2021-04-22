<?php

namespace App\Http\Filters\KPI;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\KPI\Query\DepartmentWhereHas;
use App\Http\Filters\KPI\Query\PositionWhereHas;
use App\Http\Filters\KPI\Query\PeriodIn;
use App\Http\Filters\KPI\Query\StatusEvaluateIn;
use App\Http\Filters\KPI\Query\YearPeriodWhereHas;

class EvaluationDetailFilter extends AbstractFilter
{
    protected $filters = [
        // 'position_id' => PositionWhereHas::class,
        // 'department_id' => DepartmentWhereHas::class,
        // 'status' => StatusEvaluateIn::class,
        // 'year' => YearPeriodWhereHas::class,
        // 'period' => PeriodIn::class,
    ];
}
