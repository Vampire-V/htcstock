<?php

namespace App\Services\KPI\Service;

use App\Enum\KPIEnum;
use App\Models\KPI\Rule;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class RuleService extends BaseService implements RuleServiceInterface
{
    protected $periodService;
    /**
     * UserService constructor.
     *
     * @param Rule $model
     */
    public function __construct(Rule $model, TargetPeriodServiceInterface $targetPeriodServiceInterface)
    {
        parent::__construct($model);
        $this->periodService = $targetPeriodServiceInterface;
    }

    public function all(): Builder
    {
        try {
            return Rule::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown($group = null): Collection
    {
        try {
            if (is_null($group)) {
                return Rule::all();
            } else {
                return Rule::with('category')
                    ->where('category_id', $group)->get();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        return Rule::with(['category', 'user', 'ruleType'])
            ->filter($request)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function isName(string $var): bool
    {
        try {
            if (Rule::where('name', $var)->first()) {
                return \true;
            }
            return \false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function rulesInEvaluationReport(string $year)
    {
        try {
            $rules = Rule::with('evaluatesDetail.evaluate')->get();
            $periods = $this->periodService->query()->where('year',$year)->get();
            foreach ($rules as $key => $rule) {
                $total = \collect();
                foreach ($periods as $period) {
                    $data_for_sum = $rule->evaluatesDetail->filter(function ($evaluate) use ($period) {
                        return $evaluate->evaluate->status === KPIEnum::approved && $period->id === $evaluate->evaluate->period_id;
                    });
                    $data_for_sum->sum('target');
                    $data_for_sum->sum('actual');
                    $total_target = $data_for_sum->count() ? $data_for_sum->sum('target') : 0.00;
                    $total_actual = $data_for_sum->count() ? $data_for_sum->sum('actual') : 0.00;
                    $total->push((object)['target' => $total_target, 'actual' => $total_actual]);
                }
                $rule->total = $total;
            }
            return $rules;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
