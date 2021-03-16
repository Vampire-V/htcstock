<?php

namespace App\Services\KPI\Service;

use App\Models\KPI\Evaluate;
use App\Models\User;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EvaluateService extends BaseService implements EvaluateServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param Evaluate $model
     */
    public function __construct(Evaluate $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return Evaluate::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function byStaff(User $staff)
    {
        try {
            return Evaluate::where('user_id', $staff->id)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return Evaluate::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function isDuplicate(int $user, int $period)
    {
        try {
            $evaluation = Evaluate::firstWhere(['user_id' => $user, 'period_id' => $period]);
            return \is_null($evaluation) ? false : $evaluation;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findKeyEvaluate(int $user, int $period, int $evaluate)
    {
        try {
            $evaluation = Evaluate::firstWhere(['user_id' => $user, 'period_id' => $period, 'id' => $evaluate]);
            return \is_null($evaluation) ? false : $evaluation;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
