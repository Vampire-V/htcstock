<?php

namespace App\Enum;

abstract class UserEnum extends BasicEnum
{
    const SUPERADMIN = 'super-admin';
    const ADMINIT = 'admin-it';
    const USERIT = 'user-it';
    const ADMINLEGAL = 'admin-legal';
    const USERLEGAL = 'user-legal';
    const OPERATIONKPI = 'admin-kpi';
    const USERKPI = 'user-kpi';
    const MANAGERKPI = 'manager-kpi';
    const ADMINKPI = 'parent-admin-kpi';

    // url profile
    const path = "/images/avatars/2.jpg";

}
