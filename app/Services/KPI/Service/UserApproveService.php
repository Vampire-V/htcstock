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

    public function findFirstLevel($id): ?UserApprove
    {
        try {
            return UserApprove::where('user_id', $id)->where('level', 1)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findNextLevel(Evaluate $evaluate): ?UserApprove
    {
        try {
            $level_approve = UserApprove::with('approveBy')->where('user_id', $evaluate->user_id)->orderBy('level')->get();
            if ($level_approve->count() === 0) {
                return new UserApprove();
            }
            if ($evaluate->next_level) {
                $userIndex = $level_approve->search(fn ($item) => $item->id === $evaluate->next_level);
                if ($level_approve->count() === ($userIndex + 1)) {
                    return $level_approve[$userIndex];
                } else {
                    return $level_approve[$userIndex + 1];
                }
            } else {
                return $level_approve->first();
            }
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
}
