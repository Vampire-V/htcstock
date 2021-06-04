<?php

namespace App\Http\Controllers\KPI\Traits;

use App\Enum\KPIEnum;
use App\Models\KPI\EvaluateDetail;
use Illuminate\Support\Collection;

trait CalculatorEvaluateTrait
{

    protected function calculation_summary(Collection $evaluations)
    {
        foreach ($evaluations as $key => $value) {
            if ($value->evaluateDetail) {
                foreach ($value->evaluateDetail as $key => $item) {
                    // \dump($item->rule->name);
                    $this->findAch($item);
                    $this->findCal($item, $item->ach);
                }
            }
        }
    }

    private function findAch(EvaluateDetail $item)
    {
        if ($item->rule->calculate_type === KPIEnum::positive) {
            $item->ach = ($item->actual / $item->target) * 100.00;
        }
        if ($item->rule->calculate_type === KPIEnum::negative) {
            $item->ach = (2 - ($item->actual / $item->target)) * 100.00;
        }
        if ($item->rule->calculate_type === KPIEnum::zero_oriented_kpi) {
            $item->ach = $item->actual <= $item->target ? 100.00 : 0.00;
        }
    }

    private function findCal(EvaluateDetail $item, $ach)
    {
        if ($ach < $item->base_line) {
            $item->cal = 0.00;
        } else {
            if ($ach >= $item->max) {
                $item->cal = ($item->max * $item->weight) / 100.00;
            } else {
                $item->cal = ($ach * $item->weight) / 100.00;
            }
        }
    }
}
