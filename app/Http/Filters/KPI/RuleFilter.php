<?php

namespace App\Http\Filters\KPI;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\KPI\Query\CategoryWhereIn;
use App\Http\Filters\KPI\Query\NameLike;

class RuleFilter extends AbstractFilter
{
    protected $filters = [
        'category_id' => CategoryWhereIn::class,
        'ruleName' => NameLike::class
    ];
}
