<?php

namespace App\Models\KPI;

use App\Relations\KpiRuleTypeTrait;
use Illuminate\Database\Eloquent\Model;

class KpiRuleType extends Model
{
    use KpiRuleTypeTrait;
    protected $guarded = [];
}
