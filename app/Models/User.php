<?php

namespace App\Models;

use App\Http\Filters\All\Filter\UserFilter;
use App\Http\Filters\IT\UserManagementFilter;
use App\Models\Department;
use App\Models\IT\Transactions;
use App\Models\Legal\LegalApproval;
use App\Models\Legal\LegalApprovalDetail;
use App\Models\Legal\LegalContract;
use App\Permissions\HasPermissionsTrait;
use App\Relations\UserTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasPermissionsTrait, UserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'name_en', 'head_id', 'email', 'phone', 'username', 'password', 'department_id', 'incentive_type', 'locale'
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
