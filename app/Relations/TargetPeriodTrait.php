<?php

namespace App\Relations;

use App\Models\KPI\Evaluate;

trait TargetPeriodTrait
{
    /**
     * Get the TargetPeriod that owns the Evaluate.
     */
    public function evaluates()
    {
        return $this->hasMany(Evaluate::class, 'period_id');
    }

    /**
     * Get the TargetPeriod that owns the Evaluate.
     */
    public function evaluate()
    {
        return $this->hasOne(Evaluate::class, 'period_id')->withDefault();
    }
}
