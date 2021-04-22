<?php

namespace App\Http\Filters\KPI;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\KPI\Query\DepartmentForDetail;
use App\Http\Filters\KPI\Query\PositionWhereHas;
use App\Http\Filters\KPI\Query\PeriodIn;
use App\Http\Filters\KPI\Query\StatusEvaluateIn;
use App\Http\Filters\KPI\Query\YearPeriodForDetail;

class EvaluationDetailFilter extends AbstractFilter
{
    protected $filters = [
        // 'position_id' => PositionWhereHas::class,
        'department' => DepartmentForDetail::class,
        // 'status' => StatusEvaluateIn::class,
        'year' => YearPeriodForDetail::class,
        // 'period' => PeriodIn::class,
    ];
}
