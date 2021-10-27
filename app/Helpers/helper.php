<?php

namespace Helpers;

use App\Enum\ContractEnum;
use App\Enum\KPIEnum;
use App\Models\KPI\Rule;
use App\Models\Legal\LegalContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class Helper
{

    public static function changeDateFormate($date, $date_format)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);
    }

    public static function convertToTHB(float $amount)
    {
        return number_format($amount, 2) . " ฿ ";
    }

    public static function decimal(float $amount)
    {
        return number_format(round($amount,2,PHP_ROUND_HALF_UP),2,'.','');
    }

    public static function isActive(string $route)
    {
        return Request::is($route) ? 'mm-active' : '';
    }

    public static function getMonth()
    {
        return [
            "01" => "January", "02" => "February", "03" => "March", "04" => "April",
            "05" => "May", "06" => "June", "07" => "July", "08" => "August",
            "09" => "September", "10" => "October", "11" => "November", "12" => "December"
        ];
    }

    public static function convertQty($qty)
    {
        if (substr($qty, 0, 1) === "-") {
            return substr($qty, 1);
        }
        return $qty;
    }

    public static function makeRandomTokenKey()
    {
        return Str::random(32);
    }

    public static function setAttrActualStep(Rule $rule)
    {
        $step = 0.01;
        if ($rule->calculate_type === KPIEnum::zero_oriented_kpi) {
            $step = 1;
        }
        if ($rule->calculate_type === KPIEnum::positive || $rule->calculate_type === KPIEnum::negative) {
            $step = 0.01;
        }
        return $step;
    }

    public static function kpiStatusBadge($status): string
    {
        switch ($status) {
            case KPIEnum::new:
                return "badge badge-pill badge-info";
                break;
            case KPIEnum::ready:
                return "badge badge-pill badge-primary";
                break;
            case KPIEnum::draft:
                return "badge badge-pill badge-dark";
                break;
            case KPIEnum::on_process:
                return "badge badge-pill badge-warning";
                break;
            case KPIEnum::approved:
                return "badge badge-pill badge-success";
                break;
            default:
                return "badge badge-pill badge-light";
                break;
        }
    }

    public static function convertToMonthName($m)
    {
        return Carbon::create()->day(1)->month($m)->format('F');
    }

    public static function convertToMonthNumber($m)
    {
        return Carbon::create()->day(1)->month($m)->format('m');
    }

    public static function randomBadge()
    {
        $list = collect(["badge-primary", "badge-secondary", "badge-success", "badge-info", "badge-warning", "badge-danger", "badge-focus", "badge-alternate", "badge-dark"]);
        return $list->random();
    }

    public static function legalStatusStep(LegalContract $model, $iteration = null)
    {
        try {
            if (ContractEnum::RQ === $model->status && $iteration < 2) {
                return "done";
            }
            if (ContractEnum::CK === $model->status && $iteration < 3) {
                return "done";
            }
            if (ContractEnum::P === $model->status && $iteration < 4) {
                return "done";
            }
            if (ContractEnum::CP === $model->status && $iteration < 5) {
                return "done";
            }
            return "todo";
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
