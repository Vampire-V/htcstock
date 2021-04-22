<?php

namespace App\Services\KPI\Service;

use App\Enum\KPIEnum;
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

    // public function updateForEvaluate(array $datas, int $evaluate, int $rule_id)
    // {
    //     try {
    //         $row = EvaluateDetail::firstWhere(['evaluate_id' => $evaluate, 'rule_id' => $rule_id]);
    //         \dd($row, $datas, $evaluate, $rule_id);
    //         return EvaluateDetail::where(['id' => $row->id])->update($datas);
    //     } catch (\Throwable $th) {
    //         throw $th;
    //     }
    // }

    public function setActualFilter(Request $request)
    {
        try {
            return EvaluateDetail::with(['evaluate.user', 'evaluate.targetperiod', 'rule'])
                ->whereHas('rule', fn ($query) => $query->where('user_actual', \auth()->id()))
                ->whereHas('evaluate', fn ($query) => $query->whereNotIn('status', [KPIEnum::approved, KPIEnum::new, KPIEnum::submit]))
                ->setActualFilter($request)
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function setActualForEddyFilter(Request $request)
    {
        try {
            return EvaluateDetail::with(['evaluate.user', 'evaluate.targetperiod', 'rule'])
                // ->whereHas('rule', fn ($query) => $query->where('user_actual', \auth()->id()))
                ->whereHas('evaluate', fn ($query) => $query->whereIn('status', [KPIEnum::approved]))
                ->setActualFilter($request)
                // ->orderBy('email', 'asc')
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // public function formulaKeyTask(EvaluateDetail $object): EvaluateDetail
    // {
    //     $object->ach = 0;
    //     $object->cal = 0;
    //     if ($object->rule->calculate_type === KPIEnum::positive) {
    //         $object->ach = ($object->actual / $object->target) * 100;
    //     }
    //     if ($object->rule->calculate_type === KPIEnum::negative) {
    //         $object->ach = (2 - ($object->actual / $object->target)) * 100;
    //     }
    //     if ($object->rule->calculate_type === KPIEnum::zero_oriented_kpi) {
    //         $object->ach = $object->actual <= $object->target ? 100 : 0;
    //     }

    //     if ($object->ach < $object->base_line) {
    //         $object->cal = 0;
    //     } else if ($object->ach > $object->base_line) {
    //         $object->cal = $object->base_line * $object->weight / 100;
    //     } else {
    //         $object->cal = $object->ach * $object->weight / 100;
    //     }

    //     return $object;
    // }
}
