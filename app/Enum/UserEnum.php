<?php

namespace App\Enum;

abstract class UserEnum extends BasicEnum
{
    const SUPERADMIN = 'super-admin';
    const ADMINIT = 'admin-it';
    const USERIT = 'user-it';
    const ADMINLEGAL = 'admin-legal';
    const USERLEGAL = 'user-legal';
    const ADMINKPI = 'admin-kpi';
    const USERKPI = 'user-kpi';
    const MANAGERKPI = 'manager-kpi';

    // url profile
    const path = "/images/avatars/unknown.jpg";

}
