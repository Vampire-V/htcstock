<?php

namespace App\Exports\KPI;

use App\Models\KPI\Evaluate;
use App\Models\KPI\RuleCategory;
// use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class EvaluateExport implements FromView
{
    public function __construct(Evaluate $model, $rules)
    {
        $this->evaluate = $model;
        $this->rules = $rules;
        $this->category = RuleCategory::all();
    }
    public function view(): View
    {
        if ($this->rules->count() > 2) {
            $weight_group = config('kpi.weight')['quarter'];
        } else {
            $weight_group = config('kpi.weight')['month'];
        }
        
        return view('kpi.SelfEvaluation.Excel.excelevaluate', [
            'evaluate' => $this->evaluate,
            'evaluate_detail' => $this->rules,
            'weight_group' => $weight_group,
            'category' => $this->category
        ]);
    }
}
