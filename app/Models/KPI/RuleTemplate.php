<?php

namespace App\Models\KPI;

use App\Relations\RuleTemplateTrait;
use Illuminate\Database\Eloquent\Model;

class RuleTemplate extends Model
{
    use RuleTemplateTrait;
    protected $table = 'kpi_rule_templates';
    protected $casts = [
        'weight' => 'float',
        'weight_category' => 'float',
        'target_config' => 'float',
        'base_line' => 'float',
        'max_result' => 'float',
        'parent_rule_template_id' => 'int',
        'amount' => 'float'
    ];

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
        'max_result',
        'amount'
    ];
}
