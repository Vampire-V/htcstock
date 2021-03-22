<?php

namespace App\Models;

use App\Relations\PermissionTrait;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
   use PermissionTrait;
}
