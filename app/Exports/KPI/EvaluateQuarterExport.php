<?php

namespace App\Exports\KPI;

use App\Enum\KPIEnum;
use App\Models\KPI\Evaluate;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class EvaluateQuarterExport implements FromView
{
    public function __construct(User $user, Evaluate $evaluate, Collection $detail)
    {
        $this->user = $user;
        $this->evaluate = $evaluate;
        $this->rules = $detail->groupBy('rule.category.name');
    }
    public function view(): View
    {
        $quarter_weight = $this->user->degree !== KPIEnum::four ? config('kpi.weight')['quarter'] : config('kpi.weight')['month'];
        return view('kpi.SelfEvaluation.Excel.excelevaluatequarter', ['rules' => $this->rules, 'user' => $this->user, 'quarter_weight' => $quarter_weight, 'evaluate' => $this->evaluate]);
    }
}
