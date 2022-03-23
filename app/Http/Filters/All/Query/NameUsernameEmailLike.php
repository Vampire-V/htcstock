<?php

namespace App\Http\Filters\All\Query;

class NameUsernameEmailLike
{
    public function filter($builder, $value)
    {
        return $builder->orWhereTranslationLike('name', '%' . $value . '%')
        ->orWhere('username', 'like', '%' . $value . '%')
        ->orWhere('email', 'like', '%' . $value . '%')
        ->orWhere('degree', 'like', '%' . $value . '%');
    }
}
