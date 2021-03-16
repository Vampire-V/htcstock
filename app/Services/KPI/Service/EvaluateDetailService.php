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

    public function updateForEvaluate(array $datas, int $id,int $evaluate,int $rule_id)
    {
        try {
            return EvaluateDetail::where(['id' => $id,'evaluate_id' => $evaluate,'rule_id' => $rule_id])->update($datas);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
