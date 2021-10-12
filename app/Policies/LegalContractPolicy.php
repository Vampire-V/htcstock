<?php

namespace App\Policies;

use App\Enum\ContractEnum;
use App\Enum\UserEnum;
use App\Models\Legal\LegalContract;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class LegalContractPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // return Gate::allows('for-superadmin') || Gate::allows('for-adminlegal') || Gate::allows('for-userlegal');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Legal\LegalContract  $legalContract
     * @return mixed
     */
    public function view(User $user, LegalContract $legalContract)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can(UserEnum::USERLEGAL);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Legal\LegalContract  $legalContract
     * @return mixed
     */
    public function update(User $user, LegalContract $legalContract)
    {
        return $user->id === $legalContract->created_by && $legalContract->status === ContractEnum::D;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Legal\LegalContract  $legalContract
     * @return mixed
     */
    public function delete(User $user, LegalContract $legalContract)
    {
        return $user->id === $legalContract->created_by && $legalContract->status === ContractEnum::D;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Legal\LegalContract  $legalContract
     * @return mixed
     */
    public function restore(User $user, LegalContract $legalContract)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Legal\LegalContract  $legalContract
     * @return mixed
     */
    public function forceDelete(User $user, LegalContract $legalContract)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Legal\LegalContract  $legalContract
     * @return mixed
     */
    public function deleteOrEdit(User $user, LegalContract $legalContract)
    {
        return ($legalContract->status === ContractEnum::RQ) && ($user->id === $legalContract->created_by);
    }

    /**
     * Determine whether the user can view the model request status.
     *
     * @param  \App\Models\Legal\LegalContract  $legalContract
     * @return mixed
     */
    public function isRequest(?User $user,LegalContract $legalContract)
    {
        return $legalContract->status === ContractEnum::RQ;
    }

    /**
     * Determine whether the user can view the model request status.
     *
     * @param  \App\Models\Legal\LegalContract  $legalContract
     * @return mixed
     */
    public function isChecking(?User $user,LegalContract $legalContract)
    {
        return $legalContract->status === ContractEnum::CK;
    }

    /**
     * Determine whether the user can view the model request status.
     *
     * @param  \App\Models\Legal\LegalContract  $legalContract
     * @return mixed
     */
    public function isProviding(?User $user,LegalContract $legalContract)
    {
        return $legalContract->status === ContractEnum::P;
    }

    /**
     * Determine whether the user can view the model complete status.
     *
     * @param  \App\Models\Legal\LegalContract  $legalContract
     * @return mixed
     */
    public function isComplete(?User $user,LegalContract $legalContract)
    {
        return $legalContract->status === ContractEnum::CP;
    }
}
