<?php

namespace App\Relations;

use App\Models\KPI\Rule;
use App\Models\KPI\Template;

trait RuleTemplateTrait
{
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id')->withDefault();
    }

    public function rule()
    {
        return $this->belongsTo(Rule::class, 'rule_id')->withDefault();
    }
}
