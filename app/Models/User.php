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
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasPermissionsTrait;

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

    public function systems()
    {
        return $this->belongsToMany(System::class, 'users_has_systems', 'user_id', 'system_id');
    }
    public function divisions()
    {
        return $this->belongsTo(Division::class, 'divisions_id')->withDefault();
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }
    public function positions()
    {
        return $this->belongsTo(Position::class, 'positions_id')->withDefault();
    }


    // ITSTOCK
    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'trans_by')->withDefault();
    }

    public function createdTransaction()
    {
        return $this->belongsTo(Transactions::class, 'created_by')->withDefault();
    }


    // Legal
    public function requestorContract()
    {
        return $this->hasMany(LegalContract::class);
    }

    public function checkedContract()
    {
        return $this->hasMany(LegalContract::class, 'checked_by');
    }

    public function createdContract()
    {
        return $this->hasMany(LegalContract::class);
    }

    public function legalApprove()
    {
        return $this->hasMany(LegalApproval::class);
    }

    public function approvalDetail()
    {
        return $this->hasMany(LegalApprovalDetail::class);
    }
}
