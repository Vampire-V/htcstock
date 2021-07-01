<?php

namespace App\Services\KPI\Service;

use App\Enum\KPIEnum;
use App\Models\KPI\UserApprove;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Database\Eloquent\Builder;
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

    public function user($id): Collection
    {
        try {
            return UserApprove::where('user_id', $id)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
