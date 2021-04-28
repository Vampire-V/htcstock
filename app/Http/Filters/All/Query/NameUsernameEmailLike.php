<?php

namespace App\Http\Filters\All\Query;

class NameUsernameEmailLike
{
    public function filter($builder, $value)
    {
        return $builder->orWhere('name_th', 'like', '%' . $value . '%')
            ->orWhere('name_en', 'like', '%' . $value . '%')
            ->orWhere('username', 'like', '%' . $value . '%')
            ->orWhere('email', 'like', '%' . $value . '%');
    }
}
