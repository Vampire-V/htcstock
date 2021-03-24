<?php

namespace App\Relations;

use App\Models\Department;
use App\Models\Division;
use App\Models\IT\Transactions;
use App\Models\KPI\Evaluate;
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
        return $this->hasMany(Evaluate::class);
    }
}