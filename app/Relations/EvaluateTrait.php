<?php

namespace App\Relations;

use App\Models\KPI\EvaluateDetail;
use App\Models\KPI\EvaluatesHistory;
use App\Models\KPI\TargetPeriod;
use App\Models\KPI\Template;
use App\Models\KPI\UserApprove;
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

    /**
     * Get the UserApprove that owns the Evaluate.
     */
    public function userApprove()
    {
        return $this->hasMany(UserApprove::class,'user_id','user_id');
    }

    public function history()
    {
        return $this->hasMany(EvaluatesHistory::class);
    }

}
