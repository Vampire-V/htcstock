<?php

namespace App\Models;

use App\Enum\UserEnum;
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
    // protected $fillable = [
    //     'name_th', 'name_en', 'head_id', 'email', 'phone', 'username', 'password', 'department_id', 'incentive_type', 'locale', 'divisions_id', 'positions_id', 'resigned', 'degree', 'image'
    // ];
    protected $guarded = [];

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
        'email_verified_at' => 'datetime'
        // 'head_id' => 'int'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    // protected $with = ['user_approves'];

    public function scopeFilter(Builder $builder, $request)
    {
        return (new UserFilter($request))->filter($builder);
    }

    /**
     * Get the user's first image.
     *
     * @param  string  $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        return $value ?? UserEnum::path;
    }

    public function scopeResigned(Builder $query)
    {
        return $query->whereIn('resigned', [1]);
    }

    public function scopeNotResigned(Builder $query)
    {
        return $query->whereIn('resigned', [0]);
    }

    public function scopeOfDivision(Builder $query)
    {
        return $query->whereIn('divisions_id', [\auth()->user()->divisions_id]);
    }

    public function scopeKpiHided(Builder $query)
    {
        return $query->where('kpi_hided', 1);
    }

    public function scopeKpiNotHided(Builder $query)
    {
        return $query->where('kpi_hided', 0);
    }
}
