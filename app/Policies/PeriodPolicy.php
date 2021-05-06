<?php

namespace App\Policies;

use App\Models\KPI\TargetPeriod;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PeriodPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KPI\TargetPeriod  $targetPeriod
     * @return mixed
     */
    public function view(User $user, TargetPeriod $targetPeriod)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        \dd($user->role);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KPI\TargetPeriod  $targetPeriod
     * @return mixed
     */
    public function update(User $user, TargetPeriod $targetPeriod)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KPI\TargetPeriod  $targetPeriod
     * @return mixed
     */
    public function delete(User $user, TargetPeriod $targetPeriod)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KPI\TargetPeriod  $targetPeriod
     * @return mixed
     */
    public function restore(User $user, TargetPeriod $targetPeriod)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\KPI\TargetPeriod  $targetPeriod
     * @return mixed
     */
    public function forceDelete(User $user, TargetPeriod $targetPeriod)
    {
        //
    }
}
