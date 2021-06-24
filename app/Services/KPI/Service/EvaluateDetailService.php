<?php

namespace App\Services\KPI\Service;

use App\Enum\KPIEnum;
use App\Enum\UserEnum;
use App\Models\KPI\EvaluateDetail;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluateDetailService extends BaseService implements EvaluateDetailServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param EvaluateDetail $model
     */
    public function __construct(EvaluateDetail $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return EvaluateDetail::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return EvaluateDetail::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function setActualFilter(Request $request)
    {
        try {
            $result = EvaluateDetail::with(['evaluate.user', 'evaluate.targetperiod', 'rule.category'])
                ->whereHas('rule', fn ($query) => $query->where('user_actual', \auth()->id()))
                ->whereHas('evaluate', fn ($query) => $query->whereNotIn('status', [KPIEnum::approved, KPIEnum::new, KPIEnum::submit]))
                // ->whereHas('evaluate.user', fn ($query) => $query->where('divisions_id', \auth()->user()->divisions_id))
                ->setActualFilter($request)
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
        return $result;
    }

    public function findLastRule($rule_id)
    {
        try {
            return EvaluateDetail::where(['rule_id' => $rule_id])->orderBy('created_at','desc')->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
