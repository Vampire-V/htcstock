<?php

namespace App\Relations;

use App\Models\KPI\EvaluateDetail;
use App\Models\KPI\RuleCategory;
use App\Models\KPI\RuleTemplate;
use App\Models\KPI\TargetUnit;

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

    public function evaluateDetail()
    {
        return $this->hasOne(EvaluateDetail::class)->withDefault();
    }
}
