<?php

namespace App\Relations;

use App\Models\KPI\Evaluate;
use App\Models\KPI\EvaluateDetail;
use App\Models\KPI\KpiRuleType;
use App\Models\KPI\RuleCategory;
use App\Models\KPI\RuleTemplate;
use App\Models\KPI\TargetUnit;
use App\Models\User;

trait RuleTrait
{
    
    public function category()
    {
        return $this->belongsTo(RuleCategory::class, 'category_id')->withDefault();
    }

    public function ruleTemplate()
    {
        return $this->hasOne(RuleTemplate::class)->withDefault();
    }

    public function targetUnit()
    {
        return $this->belongsTo(TargetUnit::class, 'target_unit_id')->withDefault();
    }

    public function evaluate()
    {
        return $this->hasOne(Evaluate::class)->withDefault();
    }

    public function evaluateDetail()
    {
        return $this->hasOne(EvaluateDetail::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_actual')->withDefault();
    }

    public function ruleType()
    {
        return $this->belongsTo(KpiRuleType::class, 'kpi_rule_types_id')->withDefault();
    }
}
