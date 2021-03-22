<?php

namespace App\Models;

use App\Relations\PermissionTrait;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
   use PermissionTrait;

   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
      'name',
      'slug',
      'system_id'
   ];
}
