<?php

namespace App\Services\KPI\Service;

use App\Models\KPI\EvaluateDetail;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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

    public function updateForEvaluate(array $datas, int $evaluate, int $rule_id)
    {
        try {
            $row = EvaluateDetail::firstWhere(['evaluate_id' => $evaluate, 'rule_id' => $rule_id]);
            return EvaluateDetail::where(['id' => $row->id])->update($datas);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function formulaKeyTask(EvaluateDetail $object): EvaluateDetail
    {
        $ach = ($object->actual / $object->target) * 100;
        $object->ach = 0;
        $object->cal = 0;
        if ($object->rule->calculate_type === 'Amount') {
            $object->ach = $ach;
        }
        if ($object->rule->calculate_type === 'Percent') {
            $object->ach = $object->actual;
        }
        if ($object->ach < 70) {
            $object->cal = 0;
        } else if ($object->ach > $object->base_line) {
            $object->cal = $object->base_line * $object->weight / 100;
        } else {
            $object->cal = $object->ach * $object->weight / 100;
        }
        // $object->cal = $ach;
        $object->result = 0; //$cal * $object->weight;
        return $object;
    }
}
