<?php

namespace App\Services\KPI\Service;

use App\Models\KPI\TargetPeriod;
use App\Models\User;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TargetPeriodService extends BaseService implements TargetPeriodServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param TargetPeriod $model
     */
    public function __construct(TargetPeriod $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return TargetPeriod::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function byYear(string $year)
    {
        try {
            return TargetPeriod::where('year', $year)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function byYearForEvaluate(string $year, User $staff): Collection
    {
        try {
            return TargetPeriod::with(['evaluate' => function ($query) use ($staff) {
                $query->where(['user_id'=> $staff->id]);
            }])->where('year', $year)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return TargetPeriod::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
