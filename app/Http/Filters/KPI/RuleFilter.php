<?php

namespace App\Http\Filters\KPI;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\KPI\Query\CategoryWhereIn;
use App\Http\Filters\KPI\Query\NameLike;
use App\Http\Filters\KPI\Query\RuleTypeIn;

class RuleFilter extends AbstractFilter
{
    protected $filters = [
        'category_id' => CategoryWhereIn::class,
        'ruleName' => NameLike::class,
        'rule_type' => RuleTypeIn::class
    ];
}
