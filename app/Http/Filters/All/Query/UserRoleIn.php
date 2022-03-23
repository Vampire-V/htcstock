<?php
namespace App\Http\Filters\All\Query;

class UserRoleIn
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('roles', function ($q) use($value){
            $q->whereIn('roles.id',[...$value]); // or whatever constraint you need here
          });
    }
}
