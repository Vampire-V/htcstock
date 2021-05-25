<?php

namespace App\Relations;

use App\Models\Department;
use App\Models\KPI\Evaluate;
use App\Models\KPI\RuleCategory;
use App\Models\KPI\RuleTemplate;
use App\Models\User;

trait TemplateTrait
{
    public function category()
    {
        return $this->hasOne(RuleCategory::class)->withDefault();
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id')->withDefault();
    }

    public function evaluate()
    {
        return $this->hasMany(Evaluate::class, 'template_id');
    }

    public function ruleTemplate()
    {
        return $this->hasMany(RuleTemplate::class, 'template_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_created')->withDefault();
    }
}
