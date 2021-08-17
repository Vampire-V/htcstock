<?php

namespace App\Services\KPI\Service;

use App\Enum\KPIEnum;
use App\Models\KPI\Evaluate;
use App\Models\KPI\UserApprove;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserApproveService extends BaseService
{
    /**
     * UserService constructor.
     *
     * @param UserApprove $model
     */
    public function __construct(UserApprove $model)
    {
        parent::__construct($model);
    }

    public function findFirstLevel($user_id): ?UserApprove
    {
        try {
            return UserApprove::where('user_id', $user_id)->where('level', 1)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findNextLevel(Evaluate $evaluate): ?UserApprove
    {
        try {
            $user_approve = UserApprove::with('approveBy')->where(['user_id' => $evaluate->user_id, 'level' => $evaluate->next_level])->orderBy('level')->first();
            if (!$user_approve) {
                return new UserApprove();
            }
            return $user_approve;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function isLastLevel(Evaluate $evaluate): bool
    {
        // auth id ตรงกับ Last Level return true
        try {
            $level_approve = UserApprove::with('approveBy')->where('user_id', $evaluate->user_id)->orderBy('level', 'desc')->first();
            return \auth()->id() === $level_approve->user_approve ? \true : \false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function isFirstLevel(Evaluate $evaluate): bool
    {
        try {
            $level_approve = UserApprove::with('approveBy')->where('user_id', $evaluate->user_id)->orderBy('level')->first();
            return \auth()->id() === $level_approve->user_approve ? \true : \false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findLastLevel(Evaluate $evaluate): ?UserApprove
    {
        try {
            return UserApprove::where('user_id', $evaluate->user_id)->orderBy('level', 'desc')->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findCurrentLevel(Evaluate $evaluate): ?UserApprove
    {
        try {
            return UserApprove::where(['user_id'=> $evaluate->user_id,'level' => $evaluate->current_level])->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
