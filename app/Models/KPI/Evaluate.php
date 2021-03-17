<?php

namespace App\Models\KPI;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Evaluate extends Model
{
    protected $table = 'kpi_evaluates';
    protected $casts = [
        'user_id' => 'int',
        'period_id' => 'int',
        'head_id' => 'int',
        'template_id' => 'int',
        'main_rule_id' => 'int',
        'main_rule_condition_1_min' => 'float',
        'main_rule_condition_1_max' => 'float',
        'main_rule_condition_2_min' => 'float',
        'main_rule_condition_2_max' => 'float',
        'main_rule_condition_3_min' => 'float',
        'main_rule_condition_3_max' => 'float',
        'total_weight_kpi' => 'float',
        'total_weight_key_task' => 'float',
        'total_weight_omg' => 'float'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'period_id',
        'head_id',
        'status',
        'template_id',
        'main_rule_id',
        'main_rule_condition_1_min',
        'main_rule_condition_1_max',
        'main_rule_condition_2_min',
        'main_rule_condition_2_max',
        'main_rule_condition_3_min',
        'main_rule_condition_3_max',
        'total_weight_kpi',
        'total_weight_key_task',
        'total_weight_omg'
    ];

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
        return $this->belongsTo(User::class, 'user_id')->withDefault();
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
        return $this->hasMany(EvaluateDetail::class);
    }
}