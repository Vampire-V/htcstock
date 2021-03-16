<?php

namespace App\Models\KPI;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RuleTemplate extends Model
{
    protected $table = 'kpi_rule_templates';
    protected $casts = [
        'weight' => 'float',
        'weight_category' => 'float',
        'target_config' => 'float',
        'base_line' => 'float',
        'max_result' => 'float',
        'parent_rule_template_id' => 'int'
    ];
    // protected $hidden = ['rules'];
    // add the rules attribute to the array
    protected $appends = array('rules');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'rule_id',
        'weight',
        'weight_category',
        'parent_rule_template_id',
        'field',
        'target_config',
        'base_line',
        'max_result'
    ];
    // code for $this->rule attribute
    // public function getRulesAttribute($value)
    // {
    //     $rule = null;
    //     if ($this->rule) {
    //         $rule = $this->rule;
    //     }
    //     return $rule;
    // }

    // override the toArray function (called by toJson)
    // public function toArray()
    // {
    //     return parent::toArray();
    // }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id')->withDefault();
    }

    public function rule()
    {
        return $this->belongsTo(Rule::class, 'rule_id')->withDefault();
    }
}
