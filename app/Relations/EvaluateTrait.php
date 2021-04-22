<?php

namespace App\Relations;

use App\Models\KPI\EvaluateDetail;
use App\Models\KPI\Rule;
use App\Models\KPI\TargetPeriod;
use App\Models\KPI\Template;
use App\Models\User;

trait EvaluateTrait
{
    /**
     * Get the TargetPeriod that owns the Evaluate.
     */
    public function targetperiod()
    {
        return $this->belongsTo(TargetPeriod::class, 'period_id')->withDefault();
    }

    /**
     * Get the User that owns the Evaluate.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the Template that owns the Evaluate.
     */
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id')->withDefault();
    }

    /**
     * Get the EvaluateDetail that owns the Evaluate.
     */
    public function evaluateDetail()
    {
        return $this->hasMany(EvaluateDetail::class,'evaluate_id','id');
    }

    public function mainRule()
    {
        return $this->belongsTo(Rule::class, 'main_rule_id')->withDefault();
    }
}
