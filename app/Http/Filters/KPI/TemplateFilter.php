<?php

namespace App\Http\Filters\KPI;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\KPI\Query\DeptWhereInForRTemp;
use App\Http\Filters\KPI\Query\TemplateWhereIn;

class TemplateFilter extends AbstractFilter
{
    protected $filters = [
        'template_id' => TemplateWhereIn::class,
        'department_id' => DeptWhereInForRTemp::class
        
    ];
}
