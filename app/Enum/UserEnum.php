<?php

namespace App\Enum;

abstract class UserEnum extends BasicEnum
{
    const SUPERADMIN = 'super-admin';
    const ADMIN = 'admin-it';
    const USER = 'user-it';
    const ADMINLEGAL = 'admin-legal';
    const USERLEGAL = 'user-legal';
}
