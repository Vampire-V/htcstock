<?php

namespace App\Http\Filters\KPI;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\KPI\Query\DegreeWhereHas;
use App\Http\Filters\KPI\Query\DepartmentWhereHas;
use App\Http\Filters\KPI\Query\PositionWhereHas;
use App\Http\Filters\KPI\Query\EvaluationPeriodIn;
use App\Http\Filters\KPI\Query\MonthPeriodWhereHas;
use App\Http\Filters\KPI\Query\QuarterPeriodWhereHas;
use App\Http\Filters\KPI\Query\StatusEvaluateIn;
use App\Http\Filters\KPI\Query\UserWhereIn;
use App\Http\Filters\KPI\Query\YearPeriodWhereHas;

class EvaluationFilter extends AbstractFilter
{
    protected $filters = [
        'position_id' => PositionWhereHas::class,
        'department_id' => DepartmentWhereHas::class,
        'status' => StatusEvaluateIn::class,
        'year' => YearPeriodWhereHas::class,
        'month' => MonthPeriodWhereHas::class,
        'quarter' => QuarterPeriodWhereHas::class,
        'period' => EvaluationPeriodIn::class,
        'user' => UserWhereIn::class,
        'degree' => DegreeWhereHas::class
    ];
}
