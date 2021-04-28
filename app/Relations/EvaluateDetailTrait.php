<?php

namespace App\Relations;

use App\Models\KPI\Evaluate;
use App\Models\KPI\Rule;

trait EvaluateDetailTrait
{
    /**
     * Get the Evaluate that owns the EvaluateDetail.
     */
    public function evaluate()
    {
        return $this->belongsTo(Evaluate::class, 'evaluate_id')->withDefault();
    }

    /**
     * Get the Evaluate that owns the Rule.
     */
    public function rule()
    {
        return $this->belongsTo(Rule::class, 'rule_id')->withDefault();
    }

        /**
     * Get the Evaluate that owns the Rule.
     */
    public function rules()
    {
        return $this->belongsTo(Rule::class, 'rule_id');
    }
}
