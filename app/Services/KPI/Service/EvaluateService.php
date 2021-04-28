<?php

namespace App\Services\KPI\Service;

use App\Enum\KPIEnum;
use App\Models\KPI\Evaluate;
use App\Models\User;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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

    public function findId($id)
    {
        try {
            return Evaluate::with([
                'user',
                'targetperiod',
                'template' => fn ($q) => $q->with(['ruleTemplate' => fn ($q) => $q->with(['rule' => fn ($q) => $q->with('category')])]),
                'evaluateDetail' => fn ($q) => $q->with(['rule' => fn ($q) => $q->with('category')])
            ])->find($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function all(): Builder
    {
        try {
            return Evaluate::query();
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
            $evaluation = Evaluate::with(['evaluateDetail'])->firstWhere(['user_id' => $user, 'period_id' => $period, 'id' => $evaluate]);
            return \is_null($evaluation) ? false : $evaluation;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function reviewFilter(Request $request)
    {
        try {
            return Evaluate::with(['user' => function ($query) {
                $query->with(['department', 'positions'])->where('department_id', \auth()->user()->department_id);
            }, 'targetperiod'])
                ->whereIn('status', [KPIEnum::submit, KPIEnum::approved])
                ->filter($request)->orderBy('created_at', 'desc')
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function selfFilter(Request $request)
    {
        try {
            return Evaluate::with(['targetperiod' => function ($query) {
                $query->orderBy('id', 'desc');
            }])
                ->where('user_id', \auth()->user()->id)
                ->whereNotIn('status', [KPIEnum::new])
                ->filter($request)
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editEvaluateFilter(Request $request): Collection
    {
        try {
            return Evaluate::with([
                'user', 'targetperiod', 'evaluateDetail.rules.category', 'evaluateDetail.rules.ruleType', 'evaluateDetail.evaluate.user', 'evaluateDetail.evaluate.targetperiod'
            ])
                ->whereIn('status', [KPIEnum::approved])
                ->filter($request)
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
