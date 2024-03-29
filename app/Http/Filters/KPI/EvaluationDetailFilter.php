<?php

namespace App\Http\Filters\KPI;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\KPI\Query\CategoryForDetail;
use App\Http\Filters\KPI\Query\DepartmentForDetail;
use App\Http\Filters\KPI\Query\PeriodForDetail;
use App\Http\Filters\KPI\Query\RuleForDetail;
use App\Http\Filters\KPI\Query\UserForDetail;
use App\Http\Filters\KPI\Query\YearPeriodForDetail;

class EvaluationDetailFilter extends AbstractFilter
{
    protected $filters = [
        'user' => UserForDetail::class,
        'department' => DepartmentForDetail::class,
        'category' => CategoryForDetail::class,
        'year' => YearPeriodForDetail::class,
        'period' => PeriodForDetail::class,
        'rule' => RuleForDetail::class
    ];
}
