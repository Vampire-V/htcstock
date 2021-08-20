<?php

namespace App\Http\Controllers\KPI\Traits;

use App\Enum\KPIEnum;
use App\Models\KPI\EvaluateDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait CalculatorEvaluateTrait
{

    protected function calculation_summary(Collection $evaluations, Request $request = null)
    {
        if ($request) {
            for ($i = 0; $i < $evaluations->count(); $i++) {
                $value = $evaluations[$i];

                if ($request->has('quarter') && $request->degree === KPIEnum::one) {
                    $value->weigth = config('kpi.weight')['quarter'];
                } else {
                    $value->weigth = config('kpi.weight')['month'];
                }
                $this->calculation_detail($value->evaluateDetail);
                // dd($value,$value->evaluateDetail->groupBy(fn($item) => $item->rule->category_id));
            }
        } else {
            for ($i = 0; $i < $evaluations->count(); $i++) {
                $value = $evaluations[$i];
                if ($value->user->degree === KPIEnum::one) {
                    $value->weigth = config('kpi.weight')['quarter'];
                }else{
                    $value->weigth = config('kpi.weight')['month'];
                }
                $this->calculation_detail($value->evaluateDetail);
            }
        }
    }

    protected function calculation_detail(Collection $evaluate_detail)
    {
        if ($evaluate_detail) {
            for ($i = 0; $i < $evaluate_detail->count(); $i++) {
                $item = $evaluate_detail[$i];
                $this->findTargetPC($item, $evaluate_detail);
                $this->findActualPC($item, $evaluate_detail);
                $this->findAch($item);
                $this->findCal($item, $item->ach);
                // $item->reduce_point = $item->rule->category_id === 2 ? 10 : 0;
            }
        }
    }

    private function findAch(EvaluateDetail $item)
    {
        if (!$item->rule->parent) {
            $ac = $item->actual;
            $tar = $item->target;
            if ($item->rule->calculate_type === KPIEnum::positive) {
                // if ($ac >= $tar) {
                //     $item->ach = $item->max_result ?? $item->max;
                // } else if($ac === 0.00) {
                //     $item->ach = 0.00;
                // }else{
                //     $item->ach = ($ac / $this->isZeroNew($tar)) * 100.00;
                // }

                if ($tar === 0.00 && $ac > $tar) {
                    $item->ach = $item->max_result ?? $item->max;
                } else if ($ac === 0.00) {
                    $item->ach = 0.00;
                } else {
                    $item->ach = ($ac / $this->isZeroNew($tar)) * 100.00;
                }
            }
            if ($item->rule->calculate_type === KPIEnum::negative) {
                // if ($ac > $tar) {
                //     $item->ach = (2 - ($ac / $this->isZeroNew($tar))) * 100.00;
                // } else {
                //     $item->ach = $item->max_result ?? $item->max;
                // }
                $dd = ($ac / $this->isZeroNew($tar));
                // if ($ac !== 0.00) {
                $item->ach =  (2 - $dd) * 100.00;
                // } else{
                //     $item->ach = 0.00;
                // }
                // $dd = (obj.actual / obj.target)
                // if (dd === -Infinity) {
                //     dd = 0
                // }
                // ach = parseFloat((2 - dd ) * 100.00)
            }
            if ($item->rule->calculate_type === KPIEnum::zero_oriented_kpi) {
                $item->ach = $ac <= $tar ? 100.00 : 0.00;
            }
        } else {
            $ac = $item->actual_pc;
            $tar = $item->target_pc;
            if ($item->rule->calculate_type === KPIEnum::positive) {
                // if ($ac >= $tar) {
                //     $item->ach = ($ac / $this->isZeroNew($tar)) * 100.00;
                // } else if($ac === 0.00) {
                //     $item->ach = 0.00;
                // }else{
                //     $item->ach = ($ac / $this->isZeroNew($tar)) * 100.00;
                // }

                if ($tar === 0.00 && $ac > $tar) {
                    $item->ach = $item->max_result ?? $item->max;
                } else if ($ac === 0.00) {
                    $item->ach = 0.00;
                } else {
                    $item->ach = ($ac / $this->isZeroNew($tar)) * 100.00;
                }
            }
            if ($item->rule->calculate_type === KPIEnum::negative) {
                // if ($ac > $tar) {
                //     $item->ach = (2 - ($ac / $this->isZeroNew($tar))) * 100.00;
                // } else{
                //     $item->ach = $item->max_result ?? $item->max;
                // }

                $dd = ($ac / $this->isZeroNew($tar));
                // if ($ac !== 0.00) {
                if ($tar === 0.00) {
                    $item->ach = 0.00;
                } else {
                    $item->ach = (2 - $dd) * 100.00;
                }



                // } else{
                //     $item->ach = 0.00;
                // }
                // $dd = (obj.actual / obj.target)
                // if (dd === -Infinity) {
                //     dd = 0
                // }
                // ach = parseFloat((2 - dd ) * 100.00)
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
        if ($object->rule->parent) {
            $index = $collection->search(fn ($item) => $item->rule_id === $object->rule->parent);
            $parent = $collection[$index];
            $target = $object->target_config ?? $object->target;
            $parent_target = $parent->target_config ?? $parent->target;

            if ($target > $parent_target) {
                $object->target_pc = 0.00;
            } else if ($target === 0.00 && $parent_target === 0.00) {
                $object->target_pc = 0.00;
            } else {
                $object->target_pc = ($target / $this->isZeroNew($parent_target)) * 100;
            }
        } else {
            $object->target_pc = 100.00;
        };
    }

    private function findActualPC(EvaluateDetail $object, Collection $collection)
    {
        if ($object->rule->parent) {
            $index = $collection->search(fn ($item) => $item->rule_id === $object->rule->parent);
            $parent = $collection[$index];

            if ($object->rule->calculate_type === KPIEnum::positive) {
                if ($object->actual > $parent->actual) {
                    $object->actual_pc = 0.00;
                } else if ($object->actual === 0.00) {
                    $object->actual_pc = 0.00;
                } else {
                    $object->actual_pc = ($object->actual / $this->isZeroNew($parent->actual)) * 100;
                }
            }
            if ($object->rule->calculate_type === KPIEnum::negative) {
                // if ($object->actual > $parent->actual) {
                //     $object->actual_pc = 0.00;
                // }else if ($object->actual === 0.00) {
                //     $object->actual_pc = 0.00;
                // } else {
                //     $object->actual_pc = ($object->actual / $this->isZeroNew($parent->actual)) * 100;
                // }
                if ($parent->actual > $object->actual) {
                    $object->actual_pc = ($object->actual / $this->isZeroNew($parent->actual)) * 100.00;
                } else {
                    $object->actual_pc = 0.00;
                }
            }
            if ($object->rule->calculate_type === KPIEnum::zero_oriented_kpi) {
                $object->actual_pc = $object->actual <= $parent->actual ? 100.00 : 0.00;
            }
        } else {
            if ($object->rule->calculate_type === KPIEnum::positive) {
                if ($object->actual > $object->target) {
                    $object->actual_pc = 100.00;
                } else if ($object->actual === 0.00) {
                    $object->actual_pc = 0.00;
                } else {
                    $object->actual_pc = ($object->actual / $this->isZeroNew($object->target)) * 100;
                }
            }
            if ($object->rule->calculate_type === KPIEnum::negative) {
                // if ($object->actual > $object->target) {
                //     $object->actual_pc = 100.00;
                // }else if ($object->actual === 0.00) {
                //     $object->actual_pc = 0.00;
                // } else {
                //     $object->actual_pc = ($object->actual / $this->isZeroNew($object->target)) * 100;
                // }
                if ($object->actual > $object->target) {
                    $object->actual_pc = (($object->actual / $this->isZeroNew($object->target))) * 100.00;
                } else {
                    $object->actual_pc = 100.00;
                }
            }
            if ($object->rule->calculate_type === KPIEnum::zero_oriented_kpi) {
                $object->actual_pc = $object->actual <= $object->target ? 100.00 : 0.00;
            }

            // $object->actual_pc = ($object->actual / $this->isZeroNew($object->target)) * 100;
        };
    }

    private function isZero($actual = 0.00, $target = 0.00)
    {
        if ($actual === 0.00 && $target === 0.00) {
            return \true;
        }
        return \false;
    }

    private function isZeroNew($ff = 0.00)
    {
        return $ff === 0.00 ? 1 : $ff;
    }
}
