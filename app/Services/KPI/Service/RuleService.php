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
use Illuminate\Support\Facades\DB;

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
        return Rule::with(['category', 'user', 'ruleType', 'updatedby'])
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

    // Service ที่เรียก
    public function rulesInEvaluationReport($year)
    {
        try {
            return Rule::select('id', 'name','quarter_cal')->with(['evaluatesDetail.evaluate.targetperiod' => function ($query) use ($year) {
                return $query->select('id', 'name', 'year', 'quarter')->where('year', $year);
            },'evaluatesDetail.evaluate:id,period_id,status','evaluatesDetail:id,evaluate_id,rule_id,target,actual'])->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
