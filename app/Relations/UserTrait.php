<?php

namespace App\Relations;

use App\Models\Department;
use App\Models\Division;
use App\Models\IT\Transactions;
use App\Models\KPI\Evaluate;
use App\Models\KPI\Rule;
use App\Models\KPI\SettingAction;
use App\Models\KPI\TargetPeriod;
use App\Models\KPI\Template;
use App\Models\KPI\UserApprove;
use App\Models\Legal\LegalApproval;
use App\Models\Legal\LegalApprovalDetail;
use App\Models\Legal\LegalContract;
use App\Models\Permission;
use App\Models\Position;
use App\Models\Role;
use App\Models\System;

trait UserTrait
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
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

    // KPI
    public function evaluate()
    {
        return $this->hasOne(Evaluate::class,'user_id')->withDefault();
    }

    public function evaluates()
    {
        return $this->hasMany(Evaluate::class);
    }

    public function rule()
    {
        return $this->hasOne(Rule::class)->withDefault();
    }

    public function template()
    {
        return $this->hasOne(Template::class)->withDefault();
    }

    public function setting_actions()
    {
        return $this->belongsToMany(SettingAction::class,'kpi_setting_action_user');
    }

    public function user_approves()
    {
        return $this->hasMany(UserApprove::class)->orderBy('level');
    }

    public function operationDept()
    {
        return $this->belongsToMany(Department::class, 'kpi_operation_dept');
    }
}
