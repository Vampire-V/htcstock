<?php

namespace App\Models;

use App\Http\Filters\All\Filter\UserFilter;
use App\Permissions\HasPermissionsTrait;
use App\Relations\UserTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class User extends Authenticatable implements MustVerifyEmail, TranslatableContract
{
    use Notifiable, HasPermissionsTrait, UserTrait, Translatable;

    public $translatedAttributes = ['name'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_th', 'name_en', 'head_id', 'email', 'phone', 'username', 'password', 'department_id', 'incentive_type', 'locale', 'divisions_id', 'positions_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeFilter(Builder $builder, $request)
    {
        return (new UserFilter($request))->filter($builder);
    }
}
