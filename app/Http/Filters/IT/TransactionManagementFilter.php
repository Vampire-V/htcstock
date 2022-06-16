<?php

namespace App\Http\Filters\IT;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\IT\Query\AccessorieFilter;
use App\Http\Filters\IT\Query\AccessoriesFilter;
use App\Http\Filters\IT\Query\EndAtFilter;
use App\Http\Filters\IT\Query\StartAtFilter;
use App\Http\Filters\IT\Query\IrFilter;
use App\Http\Filters\IT\Query\PoFilter;
use App\Http\Filters\IT\Query\UserWhereIn;

class TransactionManagementFilter extends AbstractFilter
{
    protected $filters = [
        'access_id' => AccessorieFilter::class,
        'accessory' => AccessoriesFilter::class,
        'user' => UserWhereIn::class,
        'ir_no' => IrFilter::class,
        'po_no' => PoFilter::class,
        'start_at' => StartAtFilter::class,
        'end_at' => EndAtFilter::class
    ];
}
