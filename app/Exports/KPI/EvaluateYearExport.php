<?php

namespace App\Exports\KPI;

use App\Enum\KPIEnum;
use App\Models\KPI\Evaluate;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class EvaluateYearExport implements FromView
{
    public function __construct(User $user,Evaluate $evaluate,Collection $detail)
    {
        $this->user = $user;
        $this->rules = $detail->groupBy('rule.category.name');
        $this->evaluate = $evaluate;
    }
    public function view(): View
    {
        $quarter_weight = $this->user->degree !== KPIEnum::four ? config('kpi.weight')['quarter'] : config('kpi.weight')['month'];
        return view('kpi.SelfEvaluation.Excel.excelevaluateyear',['rules' => $this->rules, 'user' => $this->user, 'quarter_weight' => $quarter_weight, 'evaluate' => $this->evaluate]);
    }
}
