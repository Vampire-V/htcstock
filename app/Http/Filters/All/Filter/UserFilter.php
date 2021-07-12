<?php

namespace App\Http\Filters\All\Filter;

use App\Http\Filters\AbstractFilter;
use App\Http\Filters\All\Query\DepartmentIn;
use App\Http\Filters\All\Query\DepartmentWhereID;
use App\Http\Filters\All\Query\DivisionIn;
use App\Http\Filters\All\Query\EMCGroupIn;
use App\Http\Filters\All\Query\NameUsernameEmailLike;
use App\Http\Filters\All\Query\PositionIn;
use App\Http\Filters\All\Query\UserIn;
use App\Http\Filters\All\Query\UserRoleIn;
use App\Http\Filters\All\Query\UserWhereID;

class UserFilter extends AbstractFilter
{
    protected $filters = [
        'search' => NameUsernameEmailLike::class,
        'division' => DivisionIn::class,
        'department' => DepartmentIn::class,
        'position' => PositionIn::class,
        'user_role' => UserRoleIn::class,
        'users' => UserIn::class,
        'users_where' => UserWhereID::class,
        'department_where' => DepartmentWhereID::class,
        'degree' => EMCGroupIn::class
    ];
}
