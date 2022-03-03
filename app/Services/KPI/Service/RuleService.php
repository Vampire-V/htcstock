<?php

namespace App\Services\KPI\Service;

use App\Models\KPI\Rule;
use App\Models\KPI\RuleCategory;
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
                return Rule::where('remove', '<>', 'Y')->get();
            } else {
                return Rule::with('category')
                    ->where('category_id', $group)->where('remove', '<>', 'Y')->get();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        return Rule::with(['category', 'user', 'ruleType', 'updatedby'])
            ->filter($request)
            ->where('remove', '<>', 'Y')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
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
    public function rulesInEvaluationReport(Collection $periods, Request $request)
    {
        try {
            return Rule::select('id', 'name', 'quarter_cal', 'category_id')->with(['evaluatesDetail.evaluate' => function ($query) use ($periods) {
                return $query->select('id', 'user_id', 'period_id', 'status')->whereIn('period_id', $periods->pluck('id'));
            }, 'category', 'evaluatesDetail.evaluate.user', 'evaluatesDetail:id,evaluate_id,rule_id,target,actual'])
                ->where('remove', '<>', 'Y')
                ->filter($request)
                ->orderBy('category_id')
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function rule_excel(): array
    {
        try {
            $arra = [];
            $category = RuleCategory::where('name', 'kpi')->first();
            $result = $this->dropdown($category->id);
            foreach ($result as $key => $item) {
                $arra[] = [
                    'name' => $item->name
                ];
            }
            return $arra;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function transferToUser(array $userForm, int $userTo): bool
    {
        try {
            $rules = Rule::select('id')->where('user_actual', $userForm)->get();
            $result = Rule::wherein("id",$rules->pluck('id')->toArray())->update(['user_actual' => $userTo]);
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
