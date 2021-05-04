<?php

namespace App\Http\Filters\KPI;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\KPI\Query\PeriodIn;
use App\Http\Filters\KPI\Query\YearPeriodWhere;

class PeriodFilter extends AbstractFilter
{
    protected $filters = [
        'period' => PeriodIn::class,
        'year' => YearPeriodWhere::class,
    ];
}
