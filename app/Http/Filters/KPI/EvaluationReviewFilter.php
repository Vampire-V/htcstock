<?php

namespace App\Http\Filters\KPI;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\KPI\Query\PeriodIn;
use App\Http\Filters\KPI\Query\StatusEvaluateIn;
use App\Http\Filters\KPI\Query\YearPeriodIn;

class EvaluationReviewFilter extends AbstractFilter
{
    protected $filters = [
        'status' => StatusEvaluateIn::class,
        'year' => YearPeriodIn::class,
        'period' => PeriodIn::class,
    ];
}
