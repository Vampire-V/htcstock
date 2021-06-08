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
            $this->calculation_detail($value->evaluateDetail);
        }
    }

    protected function calculation_detail(Collection $evaluate_detail)
    {
        if ($evaluate_detail) {
            foreach ($evaluate_detail as $key => $item) {
                $this->findTargetPC($item, $evaluate_detail);
                $this->findActualPC($item, $evaluate_detail);
                $this->findAch($item);
                $this->findCal($item, $item->ach);
            }
        }
    }

    private function findAch(EvaluateDetail $item)
    {

        if (!$item->rule->parent) {
            $ac = \round($item->actual, 2);
            $tar = \round($item->target, 2);
            if ($item->rule->calculate_type === KPIEnum::positive) {
                if ($ac <= 0) {
                    $item->ach = 0.00;
                } else {
                    $item->ach = $this->isZero($ac, $tar) ? 0.00 : ($ac / $tar) * 100.00;
                }
            }
            if ($item->rule->calculate_type === KPIEnum::negative) {
                $item->ach = (2 - $this->isZero($ac, $tar) ? 1 : ($ac / $tar)) * 100.00;
            }
            if ($item->rule->calculate_type === KPIEnum::zero_oriented_kpi) {
                $item->ach = $ac <= $tar ? 100.00 : 0.00;
            }
        } else {
            $ac = \round($item->actual_pc, 2);
            $tar = \round($item->target_pc, 2);
            if ($item->rule->calculate_type === KPIEnum::positive) {
                if ($ac <= 0.00) {
                    $item->ach = 0.00;
                } else {
                    $item->ach = $this->isZero($ac, $tar) ? 0 : ($ac / $tar) * 100.00;
                }
            }
            if ($item->rule->calculate_type === KPIEnum::negative) {
                $item->ach = (2 - $this->isZero($ac, $tar) ? 1 : ($ac / $tar)) * 100.00;
            }
            if ($item->rule->calculate_type === KPIEnum::zero_oriented_kpi) {
                $item->ach = $ac <= $tar ? 100.00 : 0.00;
            }
        }
    }

    private function findCal(EvaluateDetail $item, $ach)
    {
        if ($ach < $item->base_line) {
            $item->cal = 0.00;
        } else {
            $max = $item->max ?? $item->max_result;
            if ($ach >= $max) {
                $item->cal = ($max * $item->weight) / 100.00;
            } else {
                $item->cal = ($ach * $item->weight) / 100.00;
            }
        }
    }

    private function findTargetPC(EvaluateDetail $object, Collection $collection)
    {
        $object->target_pc = 100.00;
        if ($object->rule->parent) {
            $index = $collection->search(fn ($item) => $item->rule_id === $object->rule->parent);
            $parent = $collection[$index];
            $target = $object->target_config ?? $object->target;
            $parent_target = $parent->target_config ?? $parent->target;

            $object->target_pc =  $this->isZero($target, $parent_target) ? 0.00 : ($target / $parent_target) * 100;
        };
    }

    private function findActualPC(EvaluateDetail $object, Collection $collection)
    {
        $object->actual_pc = 100.00;
        if ($object->rule->parent) {
            $index = $collection->search(fn ($item) => $item->rule_id === $object->rule->parent);
            $parent = $collection[$index];
            $object->actual_pc =  $this->isZero($object->actual, $parent->actual) ? 0.00 : ($object->actual / $parent->actual) * 100;
        };
    }

    private function isZero($actual = 0, $target = 0)
    {
        if ($actual === 0 && $target === 0) {
            return \true;
        }
        return \false;
    }
}
