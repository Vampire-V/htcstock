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

    // Service ที่เรียก
    public function rulesInEvaluationReport($year)
    {
        try {
            // dd($year);
            DB::enableQueryLog();
            $rules = Rule::select('id','name')->with(['evaluatesDetail.evaluate.targetperiod' => function ($query) use($year){ 
                return $query->where('year',$year);
            } ,'evaluatesDetail.evaluate:id,user_id,template_id,status,period_id,next_level'])->get();
            $periods = $this->periodService->query()->where('year',$year)->get();
            foreach ($rules as $rule) {
                $total = \collect();
                foreach ($periods as $period) {
                    $data_for_sum = [];
                    for ($i=0; $i < $rule->evaluatesDetail->count(); $i++) { 
                        $item = $rule->evaluatesDetail[$i];
                        if ($item->evaluate->status === KPIEnum::approved && $period->id === $item->evaluate->period_id) {
                            $data_for_sum[] = $item;
                        }
                    }
                    $total->push($data_for_sum);
                }
                $rule->total = $total;
            }
            return $rules;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
