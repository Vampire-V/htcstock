<?php

namespace App\Services\KPI\Service;

use App\Enum\KPIEnum;
use App\Models\KPI\Evaluate;
use App\Models\KPI\UserApprove;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
            if (Gate::any(['for-superadmin-admin'])) {
                $result = Evaluate::with(['user.divisions', 'user.positions', 'user.department', 'targetperiod'])
                    // ->whereHas('nextlevel', fn ($query) => $query->where('user_approve', \auth()->user()->id))
                    ->whereIn('status', [KPIEnum::on_process, KPIEnum::approved])
                    ->filter($request)->orderBy('period_id', 'desc')
                    ->get();
            } else {
                $keys = UserApprove::where('user_approve',\auth()->id())->get();
                $result = Evaluate::with(['user.divisions', 'user.positions', 'user.department', 'targetperiod'])
                    // ->whereHas('nextlevel', fn ($query) => $query->where('user_approve', \auth()->id()))
                    ->whereIn('current_level', $keys->pluck('id'))
                    ->whereIn('status', [KPIEnum::on_process, KPIEnum::approved])
                    ->filter($request)->orderBy('period_id', 'desc')
                    ->get();
            }

            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function selfFilter(Request $request)
    {
        try {
            if (Gate::allows('admin-manager-kpi')) {
                $result = Evaluate::with(['user', 'targetperiod' => fn ($query) => $query->orderBy('id', 'asc')])
                    ->where('user_id', $request->user)
                    ->whereNotIn('status', [KPIEnum::new])
                    ->filter($request)->orderBy('period_id')
                    ->get();
            } else {
                $result = Evaluate::with(['user', 'targetperiod' => fn ($query) => $query->orderBy('id', 'asc')])
                    ->where('user_id', \auth()->id())
                    ->whereNotIn('status', [KPIEnum::new])
                    ->filter($request)->orderBy('period_id')
                    ->get();
            }
            // dd($result);
            return $result;
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

    public function scoreFilter(Request $request): Collection
    {
        try {
            return Evaluate::with(['user:id,username,positions_id,department_id,divisions_id,email,degree,created_at,updated_at',
                 'user.positions:id,name', 
                 'user.divisions:id,name', 
                 'targetperiod:id,name,year,quarter', 
                 'evaluateDetail.rule:id,name,calculate_type,category_id,kpi_rule_types_id,quarter_cal,parent,created_at,updated_at', 
                 'evaluateDetail.rule.category:id,name'])
                ->whereIn('status', [KPIEnum::approved])
                ->filter($request)
                ->orderBy('user_id')
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function forQuarterYear($user, $quarter, $year): Collection
    {
        try {
            return Evaluate::with('user.positions', 'user.roles', 'evaluateDetail.rule.category', 'targetperiod')
                ->whereHas('targetperiod', fn ($query) => $query->where(['quarter' => $quarter, 'year' => $year]))
                ->where(['user_id' => $user, 'status' => KPIEnum::approved])
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
