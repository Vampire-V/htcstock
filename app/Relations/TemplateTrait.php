<?php

namespace App\Relations;

use App\Models\Department;
use App\Models\KPI\RuleCategory;

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
}
