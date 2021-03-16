<?php

namespace App\Http\Filters\All\Filter;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\All\Query\DepartmentIn;
use App\Http\Filters\All\Query\DivisionIn;
use App\Http\Filters\All\Query\NameUsernameEmailLike;
use App\Http\Filters\All\Query\PositionIn;
use App\Http\Filters\All\Query\UserRoleIn;

class UserFilter extends AbstractFilter
{
    protected $filters = [
        'position_id' => PositionIn::class,
        'department_id' => DepartmentIn::class,
        'search' => NameUsernameEmailLike::class,
        'division' => DivisionIn::class,
        'user_role' => UserRoleIn::class
    ];
}
