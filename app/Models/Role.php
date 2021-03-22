<?php

namespace App\Models;

use App\Http\Filters\IT\RoleManagementFilter;
use App\Relations\RoleTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
   use RoleTrait;
   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
      'name', 'slug'
   ];

   public function scopeFilter(Builder $builder, $request)
   {
       return (new RoleManagementFilter($request))->filter($builder);
   }

   public function hasPermission($permission)
   {
      return (bool) $this->permissions->where('slug', $permission->slug)->count();
   }
}
