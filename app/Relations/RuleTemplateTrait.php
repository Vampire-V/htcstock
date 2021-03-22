<?php

namespace App\Relations;

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
